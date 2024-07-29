<?php

namespace Library;

class Cache {

	private $storage;
	private $interval;
	
	function __construct() {
		if(session_status() === PHP_SESSION_NONE) session_start();

		$this->storage  = "Public/Cache";
		$this->interval  = "+15 minutes";

	}

	function setStorage($path) {
		$directory = $this->storage."/$path";

		if (!file_exists($directory)) {
			mkdir($directory, 0777, true);
		}

		$this->storage  = $directory;
	}

	function getStorage() {
		return $this->storage;
	}

	function setRenewalInterval($time) {
		$this->interval  = $time;
	}

	function getRenewalInterval($time) {
		return $this->interval;
	}
	
	function setData(String $path, Array $data) {

		$path = explode("/",$path);
		$current = array();

		$current[$path[count($path)-1]] = array(
			"recordTime" => DATE_NOW,
			"endTime" => strtotime($this->interval, DATE_NOW),
			"data" => $data
		);

		for($i=count($path)-2; $i>-1; $i--) {
			$current[$path[$i]] = $current;
			unset($current[$path[$i+1]]);
		}

		$this->createCacheFile(implode("-",$path),$current);
		return true;
		
	}
	
	function getData(String $path) {
	
		$path = explode("/",$path);
		$cachefile = $this->storage."/cached-".implode("-",$path).".txt";

		if (file_exists($cachefile)) {
			
			$cache = file_get_contents($cachefile,"r");
			$data = json_decode($cache,true);
			
			foreach($path as $key){
				$data = $data[$key];
			}

			if($data['endTime'] <= DATE_NOW) {
				$this->removeCache($cachefile);
				return false;
			}
			
			return $data;
		}

		return false;
		
	}
	
	function removeCache(String $path) {
		
		try {
			unlink($path);
		} catch(Exception $e) {
			$logfile = "Cache/logs.txt";

			/* NEW LOGS */
			$logs = date("Y-m-d H:iA",DATE_NOW)." - $path failed to delete file!\n";

			/* OLD LOGS */
			$logs .= file_get_contents($logfile,"r");

			$cached = fopen($logfile, 'w');
			fwrite($cached, $logs);
			fclose($cached);

		} finally {
			return true;
		}
		
	}


	function createCacheFile($filename,$data) {

		$cachefile = $this->storage."/cached-$filename.txt";
		
		$cached = fopen($cachefile, 'w');
		fwrite($cached, json_encode($data,JSON_PRETTY_PRINT));
		fclose($cached);

	}
	
}