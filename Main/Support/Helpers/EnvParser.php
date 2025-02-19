<?php

namespace EO\Support\Helpers;

class EnvParser
{
	protected static $cacheFile = ROOT . '/env.cache.php';
	protected static $cacheEnabled = true;

	public function __construct($file_path)
	{
		$this->load($file_path);
	}

	public static function load($file_path, $use_cache = true)
	{
		if (self::$cacheEnabled && $use_cache && file_exists(self::$cacheFile)) {
			// Load from cache
			$_ENV = include self::$cacheFile;
		} else {
			if (!file_exists($file_path)) {
				throw new \Exception("Env file not found: $file_path");
			}

			$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($lines as $line) {
				if (strpos(trim($line), '#') === 0) {
					continue;
				}

				list($key, $value) = explode('=', $line, 2);
				$value = trim($value);
				if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
					$value = $matches[1];
				}

				$_ENV[trim($key)] = ($value === 'true') ? true : (($value === 'false') ? false : $value);
			}

			// Save to cache
			if (self::$cacheEnabled) {
				file_put_contents(self::$cacheFile, '<?php return ' . var_export($_ENV, true) . ';');
			}
		}
	}

	public static function clearCache()
	{
		if (file_exists(self::$cacheFile)) {
			unlink(self::$cacheFile);
		}
	}
}