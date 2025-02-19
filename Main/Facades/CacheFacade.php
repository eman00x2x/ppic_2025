<?php

namespace EO\Facades;

class CacheFacade
{
	protected static $cache;

	public static function setCache($cache) 
	{
		self::$cache = $cache;
	}

	public static function setRenewalInterval(String $time) 
	{
		self::$cache->setRenewalInterval($time);
	}

	public static function getData(String $path) 
	{
		return self::$cache->getData($path);
	}

	public static function setData(String $path, $data) 
	{
		return self::$cache->setData($path, $data);
	}

	public static function reCached(String $path, $data) 
	{
		return self::$cache->reCached($path, $data);
	}

	public static function removeCache(String $path) 
	{
		return self::$cache->removeCache($path);
	}

	public static function removeMultipleCache(array $path) 
	{
		return self::$cache->removeMultipleCache($path);
	}
	
	public static function clearCache() 
	{
		return self::$cache->clearCache();
	}

	public static function pruneCache() 
	{
		return self::$cache->pruneCache();
	}
}