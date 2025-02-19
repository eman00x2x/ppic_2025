<?php

namespace EO\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use EO\Handlers\Files\FileUpload;

class FileSystemHandler
{
	private Filesystem $filesystem;

	public function __construct() 
	{
        $this->filesystem = new Filesystem();
    }

	public function get(string $file_path): ?string 
	{
		if (!$this->exists($file_path)) {
           $this->filesystem->dumpFile($file_path, "");
        }
        return $this->filesystem->readFile($file_path);
	}

	public function write(string $file_path, $content): void
	{
		$this->filesystem->dumpFile($file_path, $content);
	}

	public function move(string $source_file_name, string $destination_directory, bool $rename = false): string 
	{
		if (!$this->exists($source_file_name)) {
			return '';
		}

		$extension = pathinfo($source_file_name, PATHINFO_EXTENSION);
		$destination_fileName = $rename
			? bin2hex(random_bytes(15)) . ".$extension"
			: basename($source_file_name);

		$destination_directory = pathinfo($destination_directory, PATHINFO_DIRNAME);
		if (!is_dir($destination_directory)) {
			$this->filesystem->mkdir($destination_directory, 0775);
		}

		$this->filesystem->rename($source_file_name, $destination_directory . '/' . $destination_fileName, true);
		return $destination_fileName;
	}

	public function upload(array $data = [], array $params = ['file_type' => 'image']): array 
	{
		$uploader = new FileUpload();

		if (isset($params['multiple']) && $params['multiple']) {
			$uploader->multipleUpload($data, $params);
		} else {
			$uploader->singleUpload($data, $params);
		}

		return $uploader->getResults();
	}

	public function remove(string $file_path): bool 
	{
		if ($this->exists($file_path)) {
			$this->filesystem->remove($file_path);
			return true;
		}

		return false;
	}

	public function exists(string $file_path): bool
	{
		return $this->filesystem->exists($file_path);
	}

	public function makeDir(string $path, int $mode = 0775): bool
	{
		return $this->filesystem->mkdir($path, $mode);
	}

	public function downloadToCsv($data, $header = [], $file_name = "data")
	{
		$header = [array_map(fn($val) => strtoupper(str_replace("_"," ", $val)), $header)];

		$csv_data = implode("\n", array_map(function($row) {
			return implode(",", str_replace(",", "", $row));
		}, array_merge($header, $data)));
		
		// Force download the CSV file
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $file_name . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 50');
		echo $csv_data;
		exit();
	}

}