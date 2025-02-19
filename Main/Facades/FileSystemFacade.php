<?php

namespace EO\Facades;

class FileSystemFacade
{
	protected static $filesystem;

	public static function setFileSystem($filesystem) 
	{
		self::$filesystem = $filesystem;
	}

	public static function get($file_path): ?string
	{
		return self::$filesystem->get($file_path);
	}

	public static function write(string $file_path, $content)
	{
		self::$filesystem->write($file_path, $content);
	}

	public static function move(
		string $source_file_name,
		string $destination_directory,
		bool $rename = false
	): string
	{
		return self::$filesystem->move($source_file_name, $destination_directory, $rename);
	}

	public static function upload($data, $params = ['file_type' => 'image'])
	{
		return self::$filesystem->upload($data, $params);
	}

	public static function remove(string $filePath)
	{
		return self::$filesystem->remove($filePath);
	}

	public static function downloadToCsv($data, $header = [], $file_name = "data")
	{
		return self::$filesystem->downloadToCsv($data, $header, $file_name);
	}

	public static function exists(string $file_path): bool
	{
		return self::$filesystem->exists($file_path);
	}

	public static function makeDir(string $path, int $mode = 0775): bool
	{
		return self::$filesystem->makeDir($path, $mode);
	}

}