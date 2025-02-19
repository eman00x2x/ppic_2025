<?php

namespace EO\Services;

use Ifsnop\Mysqldump\Mysqldump;
use EO\Factories\Factory;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\QueryBuilderFacade as DB;
use EO\Database\QueryBuilder;
use EO\Service;
use EO\Exceptions\DBQueryException;

class DatabaseService extends Service
{
	private $storagePath = ROOT."/Storage/Database";
	private $latestBackupFileName;
	private Mysqldump $mysqlDump;

	public function __construct()
	{
		parent::__construct();
		DB::setQueryBuilder(new QueryBuilder( Factory::DBO(), Factory::Pagination() ));

		$database_config = DB::getDatabaseConfig();
		$this->mysqlDump = new Mysqldump(
			sprintf(
				'mysql:host=%s;dbname=%s',
				$database_config['host'],
				$database_config['name']
			),
			$database_config['user'],
			$database_config['pass']
		);
	}

	public function executeQuery(string $query): array
	{
		return DB::query($query);
	}

	public function getTableList(): array
	{
		$query_result = DB::query('SHOW TABLES');

		$table_list = [];
		foreach ($query_result as $table) {
			$table_list[] = array_values($table)[0];
		}

		return $table_list;
	}

	public function backupDatabase()
	{
		$backup_file_name = sprintf(
			'backup-%s.sql',
			date('Y-m-d-G-iA', time())
		);

		try {
			$this->mysqlDump->start($this->storagePath . '/' . $backup_file_name);
		} catch (\Exception $e) {
			throw new DBQueryException($e->getMessage());
		}

		return $backup_file_name;
	}

	public function restoreDatabaseFromBackup($filename) 
	{
		$file_path = $this->storagePath . '/' . $filename;

		if (!FileSystem::exists($file_path)) {
			throw new ResourceNotFoundException("Backup file not found: $filename");
		}

		$this->latestBackupFileName = $this->backupDatabase();
		$this->deleteAllTablesFromDatabase();

		$this->mysqlDump->restore($file_path);
	}

	public function getLatestBackupFileName(): string
	{
		return $this->latestBackupFileName;
	}

	public function getDatabaseBackupFiles(): array
	{
		$files = scandir($this->storagePath);
		$files = array_diff($files, ['.', '..', '.gitignore']);
		$files = array_values($files);

		return $files;
	}

	public function downloadBackup(string $file): void
	{
		$file_path = $this->storagePath . '/' . $file;

		if (!FileSystem::exists($file_path)) {
			throw new ResourceNotFoundException("Backup file not found: $file");
		}

		header("Content-Disposition: attachment; filename=\"$file\"");
		header("Content-Description: File Transfer");
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($file_path));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		readfile($file_path);
		exit();
	}

	public function deleteBackup(string $file): void
	{
		$file_path = $this->storagePath . '/' . $file;

		if (!FileSystem::exists($file_path)) {
			throw new ResourceNotFoundException("Backup file not found: $file");
		}

		unlink($file_path);
	}

	private function deleteAllTablesFromDatabase(): void
	{
		DB::query('SET foreign_key_checks = 0');
		$tables = $this->getTableList();
		foreach ($tables as $table) {
			DB::query("DROP TABLE IF EXISTS $table");
		}
		DB::query('SET foreign_key_checks = 1');
	}

}