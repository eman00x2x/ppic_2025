<?php

namespace EO\Handlers;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use EO\Interfaces\ICache;

class CacheHandler implements ICache
{
	private $adapter = [
		"fileSystem" => FilesystemAdapter::class,
		"apcu" => ApcuAdapter::class,
		"memcached" => MemcachedAdapter::class,
		"redis" => RedisAdapter::class,
		"phpFile" => PhpFilesAdapter::class
	];

	private $cache;
	private $storage = ROOT . "/Storage/Cache/";
	private $ttl = 86400;
	private $beta = 0;

	function __construct($adapter = "fileSystem") 
	{
		$this->cache = new $this->adapter[$adapter]("", $this->ttl, $this->storage);
	}

	public function setData(string $key, mixed $data = null): mixed 
	{
		$cache_item = $this->cache->getItem($key);
		$cache_item->set($data);
		$this->cache->save($cache_item);
		return $cache_item->get();
	}

	public function getData(string $key): mixed 
	{
		$cache_item = $this->cache->getItem($key); 
		if (!$cache_item->isHit()) {
			return false;
		}
		return $cache_item->get();
	}

	public function reCached(string $key, mixed $data): mixed 
	{
		$this->removeCache($key);
		return $this->setData($key, $data);
	}

	public function setRenewalInterval(String $time) 
	{
		$this->ttl  = $time;
	}

	public function removeCache($key) 
	{
		$this->cache->deleteItem($key);
	}

	public function removeMultipleCache(array $key) 
	{
		$this->cache->deleteItems($key);
	}

	public function pruneCache()
	{
		$this->cache->prune();
	}

	public function clearCache() 
	{
		$this->cache->clear();
	}

	public function getAdapterInstance() 
	{
		return $this->cache;
	}

}