<?php

function debug($data)
{
	echo "<pre>";
	print_r($data);
	exit();
}

function getImageSrcFirstOccurrence($html) {
	$doc = new \DOMDocument();
	$doc->loadHTML($html);
	$xpath = new \DOMXPath($doc);
	return $xpath->evaluate("string(//img/@src)");
}

function clean($str)
{
	$forbidden = ["Ñ", "ñ", "'"];
	$replacement = ["N", "n", ""];

	if(!is_null($str)) {
		$str = str_replace($forbidden, $replacement, $str);
		$str = str_replace($forbidden, $replacement, $str);
		return stripslashes(trim($str));
	}
}

function escape($str)
{
	return addslashes(@trim($str));
}

function getYoutubeId($url)
{
	$parts = parse_url($url);
    if (isset($parts['host'])) {
        $host = $parts['host'];
        if (
            false === strpos($host, 'youtube') &&
            false === strpos($host, 'youtu.be')
        ) {
            return false;
        }
    }
    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        }
        else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }
    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }
    return false;
}

function getYoutubeThumbnail($id)
{
	$img["default"] = "http://img.youtube.com/vi/" . $id . "/default.jpg";
	$img["hq"] = "http://img.youtube.com/vi/" . $id . "/hqdefault.jpg";
	$img["mq"] = "http://img.youtube.com/vi/" . $id . "/mqdefault.jpg";
	$img["sd"] = "http://img.youtube.com/vi/" . $id . "/sddefault.jpg";
	$img["maxres"] = "http://img.youtube.com/vi/" . $id . "/maxresdefault.jpg";
	
	return $img;
}

function getYoutubeData($url)
{
	$data = [];
	if (($id = getYoutubeId($url)) !==false) {
		$data['id'] = $id;
		$data['thumbnail'] = getYoutubeThumbnail($id);
		$data['url'] = 'https://www.youtube.com/watch?v=' . $id;
		$data['embed'] = 'https://www.youtube.com/embed/' . $id;
		return $data;
	}
	return false;
}

function sanitize($param)
{
	$string = $param['string'] ?? throw new \Exception('Please provide a string to sanitize');
	$force_lowercase = $param['force_lowercase'] ?? true;
	$anal = $param['anal'] ?? false;
	
	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
				   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
				   "  ", "—", " -", "-", ",", "<", ".", ">", "/", "?");
	$clean = trim(str_replace($strip, "", strip_tags($string)));
	$clean = preg_replace('/\s+/', "-", $clean);
	setlocale(LC_ALL, 'en_GB');
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
	$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	return ($force_lowercase) ?
		(function_exists('mb_strtolower')) ?
			mb_strtolower($clean, 'UTF-8') :
			strtolower($clean) :
		$clean;
}

function niceTrim($param)
{
	$s = $param['string'];
	$MAX_LENGTH = $param['max_length'];

	$str_to_count = html_entity_decode($s);
	if (strlen($str_to_count) <= $MAX_LENGTH) {
		return $s;
	}

	$s2 = substr($str_to_count, 0, $MAX_LENGTH - 3);
	$s2 .= "...";
	return htmlentities($s2);
}

/**
 * Converts milliseconds to formatted time or seconds.
 * @param int [$ms] The length of the media asset in milliseconds
 * @param bool [$seconds] Whether to return only seconds
 * @return mixed The formatted length or total seconds of the media asset
 */
function convertTime($ms, $seconds = false)
{
	$total_seconds = ($ms / 1000);

	if($seconds) {
		return $total_seconds;
	} else {
		$time = '';

		$value = array(
			'hours' => 0,
			'minutes' => 0,
			'seconds' => 0
		);

		if($total_seconds >= 3600) {
			$value['hours'] = floor($total_seconds / 3600);
			$total_seconds = $total_seconds % 3600;

			$time .= $value['hours'] . ':';
		}

		if($total_seconds >= 60) {
			$value['minutes'] = floor($total_seconds / 60);
			$total_seconds = $total_seconds % 60;

			$time .= $value['minutes'] . ':';
		} else {
			$time .= '00:';
		}

		$value['seconds'] = floor($total_seconds);

		if($value['seconds'] < 10) {
			$value['seconds'] = '0' . $value['seconds'];
		}

		$time .= $value['seconds'];

		return $time;
	}

}

function convertMillions($num)
{
	if($num >= 1000000) {
		return round($num / 1000000,3)."M";
	}else if($num >= 100000) {
		return round($num / 1000,2)."K";
	}else {
		return number_format($num,0);
	}
}

/**
 * Checks if a remote file exists.
 *
 * @param string $url The URL of the remote file to check.
 * @return bool True if the file exists, false otherwise.
 */
function checkRemoteFile($url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	// don't download content
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($ch);
	curl_close($ch);

	if($result !== FALSE) {
		return true;
	} else {
		return false;
	}
}

/**
 * Converts a multi-dimensional array to a dot notation array.
 *
 * @param array $arr The input array to be converted.
 * @return array The converted array in dot notation.
 */
function arrayToDotNotation($arr)
{
	$ritit = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr));
	$result = [];
	foreach ($ritit as $leaf_value) {
		
		if(!is_null($leaf_value)) {
			json_decode($leaf_value);
			if(json_last_error() === JSON_ERROR_NONE) {
				$leaf_value = json_decode($leaf_value, true);
			}
		}

		$keys = [];
		foreach (range(0, $ritit->getDepth()) as $depth) {
			$keys[] = $ritit->getSubIterator($depth)->key();
		}
		$result[ join('.', $keys) ] = $leaf_value;
	}

	return $result;

}

/**
 * Converts an array with dot notation keys to a nested array.
 *
 * @param array $arr The input array with dot notation keys.
 * @return array The resulting nested array.
 */
function dotNotationToArray($arr)
{
	$new_array = [];
	foreach($arr as $key => $value) {
		$dots = explode(".", $key);
		if(count($dots) > 1) {
			$last = &$new_array[ $dots[0] ];
			foreach($dots as $k => $dot) {
				if($k == 0) continue;
				$last = &$last[$dot];
			}
			$last = $value;
		} else {
			$new_array[$key] = $value;
		}
	}
	return $new_array;
}

/**
 * Generates a random UUID (Universally Unique Identifier) version 4.
 *
 * @param string|null $data Optional data to use for generating the UUID. If not provided, 16 bytes of random data will be generated.
 * @return string A 36 character UUID in the format 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.
 */
function uidv4($data = null)
{
	// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
	$data = $data ?? random_bytes(16);
	assert(strlen($data) == 16);

	// Set version to 0100
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	// Set bits 6-7 to 10
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

	// Output the 36 character UUID.
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Generates a random string of a given length.
 *
 * @param int $length The length of the string to generate. Defaults to 10.
 * @return string A random string of the given length.
 */
function generateRandomString(int $length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $random_string;
}


function jsonFileToArray(string $filename): array
{
    if (!file_exists($filename) || !is_readable($filename)) {
        return [];
    }

    $content = file_get_contents($filename);
    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException(sprintf('Error parsing JSON file "%s": %s', $filename, json_last_error_msg()));
    }

    return $data;
}


/**
 * Returns a human-readable representation of a file size in bytes.
 *
 * @param int $bytes The size of the file in bytes.
 * @return string A string representing the file size in a human-readable format (e.g., 1kB, 2MB, 3GB, etc.).
 */
function readableFileSize($bytes)
{
	$i = floor(log($bytes, 1024));
	return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','kB','MB','GB','TB'][$i];
}

/**
 * Recursively removes a directory and all its contents.
 *
 * This function will delete a directory and all its subdirectories and files.
 * If the provided path is a file, it will be deleted directly.
 *
 * @param string $dir The path to the directory or file to be removed.
 *
 * @return void
 */
function removeFolderAndFiles($dir)
{
	if (is_dir($dir)) {
		$files = scandir($dir);
		
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				removeFolderAndFiles("$dir/$file");
			}
		}

		rmdir($dir);
	} else if (file_exists($dir)) {
		unlink($dir);
	}
}

/**
 * Moves a folder and all its contents from the source directory to the destination directory.
 *
 * @param string $src The path to the source directory or file to be moved.
 * @param string $dst The path to the destination directory.
 *
 * @return void
 */
function moveFolderAndFiles($src, $dst)
{
	if (file_exists($dst)) {
        moveFolderAndFiles($dst);
    }

    if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                moveFolderAndFiles("$src/$file", "$dst/$file");
            }
        }
    } else if (file_exists($src)) {
        copy($src, $dst);
    }

}

function convertToDateFilter($filter)
{
	$to = date("Y-m-d", strtotime("+12 hours"));
	$date_filters = [
		"last-7-days" => [
			"from" => date("Y-m-d", strtotime("-7 days")),
			"to" => $to
		],
		"last-30-days" => [
			"from" => date("Y-m-d", strtotime("-30 days")),
			"to" => $to
		],
		"last-60-days" => [
			"from" => date("Y-m-d", strtotime("-60 days")),
			"to" => $to
		],
		"last-90-days" => [
			"from" => date("Y-m-d", strtotime("-90 days")),
			"to" => $to
		],
		"last-6-months" => [
			"from" => date("Y-m-d", strtotime("-6 months")),
			"to" => $to
		],
		"last-12-months" => [
			"from" => date("Y-m-d", strtotime("-12 months")),
			"to" => $to
		]
	];

	return isset($date_filters[ $filter ]) ? $date_filters[ $filter ] : $filter;
}

/**
 * Checks if the authenticated user has the specified permission.
 *
 * @param string $permission The permission to check.
 * @param mixed $data pass to the permission check.
 * @return bool Returns true if the user has the permission, false otherwise.
 */
function can($permission, $data = null)
{
	return \EO\Auth\Auth::allows($permission, request()->authenticated['account'], $data);
}

function helper(callable $function, $param = [])
{
	if(is_callable($function)) {
		return $function($param);
	} else {
		throw new InvalidArgumentException("$function must be a callable function.");
	}
}