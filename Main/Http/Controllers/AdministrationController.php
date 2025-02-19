<?php

namespace EO\Http\Controllers;

use EO\View;
use EO\Auth\Auth;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\DBQueryException;
use EO\Handlers\Exceptions\AuthorizationException;
use EO\Handlers\ScheduleHandler;
use EO\Http\BaseController;
use EO\Services\DatabaseService;

class AdministrationController extends BaseController 
{
	private DatabaseService $databaseService;

	function __construct() 
	{
		if(Auth::isAdmin() === false) {
			throw new AuthorizationException("You are not authorized to access this page");
		}

		$this->databaseService = new DatabaseService();
	}
	
	function index() 
	{
		$data['tables'] = $this->databaseService->getTableList();
		return View::set(path: "/authenticated/administration/database/index.php")->bind(data: $data);
	}
	
	function queryResult() 
	{
		$request = input()->all();

		if(!isset($request['query'])) {
			return $this->handleMessageResponse('Invalid query!', 'error', 2);
		}
		
		$result = $this->databaseService->executeQuery($request['query']);

		if($result) {
			foreach($result[0] as $key => $val) {
				$data['fields'][] = $key;
			}

			$data['results'] = $result;
			View::set(path: "/authenticated/administration/database/queryResults.php")->bind(data: $data);
		}else {
			return $this->handleMessageResponse('No results found!', 'info', 2);
		}
	}

	function databaseBackupFiles()
	{
		$data['backup_files'] = $this->databaseService->getDatabaseBackupFiles();
		View::set(path: "/authenticated/administration/database/backup.php")->bind(data: $data);
	}
	
	function backupDatabase() 
	{
		try {
			$this->databaseService->backupDatabase();
		}catch(DBQueryException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}
		return $this->handleMessageResponse("Database backup created.");
	}

	function downloadBackup() 
	{
		$file = input()->get('file');
		try {
			$this->databaseService->downloadBackup($file);
		} catch(ResourceNotFoundException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}
	}

	function deleteBackup() 
	{
		$file = input()->get('file');

		try {
			$this->databaseService->deleteBackup($file);
		} catch(ResourceNotFoundException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}

		return $this->handleMessageResponse("Database backup file deleted.");
	}

	function restoreBackup()
	{
		$filename = input()->get('file');
		try {
			$this->databaseService->restoreDatabaseFromBackup($filename);
		} catch(DBQueryException $e) {
			$latest_backup_file_name = $this->databaseService->getLatestBackupFileName();
			$this->databaseService->restoreDatabaseFromBackup($latest_backup_file_name);

			return $this->handleMessageResponse($e->getMessage(). ". Restored from latest backup: $latest_backup_file_name", 'error', 2);
		}

		return $this->handleMessageResponse("Database restored from backup: $filename");
	}

	function cronTasks()
	{
		$schedule = new ScheduleHandler();
		$cron = array_merge($schedule->getTasks(), $schedule->getOtherTasks());

		foreach($cron as $key => $task) {
			$data['tasks'][] = [
				'task' => $key,
				'description' => ($task['description'] ?? $task),
				'time' => ($task['time'] ?? "Manual")
			];
		}

		View::set(path: "/authenticated/administration/cron/tasks.php")->bind(data: $data);
	}

	function cronTaskRun(string $cronTask)
	{
		$class = "\EO\Handlers\Tasks\\" . $cronTask;
		$task = new $class();
		$task->run();

		return $this->handleMessageResponse("Successfully ran cron task $cronTask");
	}

}