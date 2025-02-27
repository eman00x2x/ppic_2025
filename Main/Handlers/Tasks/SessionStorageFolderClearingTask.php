<?php

namespace EO\Handlers\Tasks;

use EO\Interfaces\ITask;
use EO\Facades\LoggerFacade as Logger;

class SessionStorageFolderClearingTask implements ITask
{
	private $folderPath = ROOT . "/Storage/Sessions";
	
	private $ignore = [
		'.',
		'..',
		'.gitignore'
	];

	public function run()
	{
		$folder_path = rtrim($this->folderPath, '/') . '/';
		$files = array_values(array_diff(scandir($folder_path), $this->ignore));
		
		$results = [
			'deleted' => [],
			'failed' => []
		];
		
		foreach ($files as $file_name) {
			$file_path = $folder_path . $file_name;
			
			if (is_file($file_path)) {
				try {
					$results['deleted'][] = $file_path;
					unlink($file_path);
				} catch (\Exception $e) {
					$results['failed'][] = [
						'file' => $file_path,
						'message' => $e->getMessage()
					];
				}
			}
		}
		
		Logger::log(
			"info",
			"Scheduler run SessionStorageFolderClearingTask",
			[
				"route" => "cron.php",
				"data" => count($results['deleted']) ? $results : "Nothing to delete"
			]
		);
	}

	public function schedule()
	{
		// every day at midnight 00:00
		return '@midnight';
	}
}