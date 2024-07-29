<?php

function debug($data) {
	
    echo "<pre>";
    print_r($data);

	exit();
	
}

function import($path,$data=null,$model=null) {

	if(file_exists($path)) {
		require_once($path);
	}else {
		$theFile1 = explode("\\",$path);
		$theFile = array_pop($theFile1);
		$html[] = " <h1 class='m-0 p-0'>File is Missing</h1> <p><br/>&mdash; <i>File</i> <b>$theFile</b> is missing in <b>".implode("\\",$theFile1)."</b> folder !</p> <hr />";
	}

    return implode("",$html);
    
}

function clean($str) {
	$forbidden = ["Ñ", "ñ", "'"];
	$replacement = ["N", "n", ""];

    if(!is_null($str)) {
        $str = str_replace($forbidden, $replacement, $str);
        $str = str_replace($forbidden, $replacement, $str);
        return stripslashes(trim($str));
    }
}

function escape($str) {
	return addslashes(@trim($str));
}

function get_message() {
	$html = @$_SESSION['msg'];
	$html .= $_SESSION['msg'] = null;

	return $html;
}

function sanitize($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

function nice_trim($param) {

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
function convert_time($ms, $seconds = false) {
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

function date_range_helper($flag) {

	switch($flag) {
		case "today":
			$data['from'] = strtotime(date("Y-m-d"));
			$data['to'] = strtotime(date("Y-m-d"));
			break;
		case "this_month":
			$data['from'] = strtotime(date("Y-m-01"));
			$data['to'] = strtotime(date("Y-m-t"));
			break;
		case "this_week":
			$today_day_of_week = date("N");
			$data['from'] = strtotime(date("Y-m-d", strtotime("-" . ($today_day_of_week - 1) . " days")));
			$data['to'] = strtotime(date("Y-m-d", strtotime("+" . (7 - $today_day_of_week) . " days")));
			break;
		case "this_year":
			$data['from'] = strtotime(date("Y-01-01"));
			$data['to'] = strtotime(date("Y-12-31"));
			break;
	}
	return $data;
}

function convert_millions($num) {

    if($num >= 1000000) {
        return round($num / 1000000,3)."M";
    }else if($num >= 100000) {
        return round($num / 1000,2)."K";
    }else {
        return number_format($num,0);
    }
    
}

function check_remote_file($url) {

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

function array_to_dot_notation($arr) {

	$ritit = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr));
	$result = [];
	foreach ($ritit as $leafValue) {
		
        if(!is_null($leafValue)) {
            json_decode($leafValue);
            if(json_last_error() === JSON_ERROR_NONE) {
                $leafValue = json_decode($leafValue, true);
            }
        }

		$keys = [];
		foreach (range(0, $ritit->getDepth()) as $depth) {
			$keys[] = $ritit->getSubIterator($depth)->key();
		}
		$result[ join('.', $keys) ] = $leafValue;
	}

	return $result;

}

function dot_notation_to_array($arr) {

	$newArray = [];
	foreach($arr as $key => $value) {
		$dots = explode(".", $key);
		if(count($dots) > 1) {
			$last = &$newArray[ $dots[0] ];
			foreach($dots as $k => $dot) {
				if($k == 0) continue;
				$last = &$last[$dot];
			}
			$last = $value;
		} else {
			$newArray[$key] = $value;
		}
	}
	return $newArray;
}

function uidv4($data = null) {
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

function readableFileSize($bytes) {
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','kB','MB','GB','TB'][$i];
}

function autoloader($class) {
	if (file_exists($file = BASE.'/'.str_replace('\\', '/', $class).'.php')) {
		require_once($file);
	}else if (file_exists($file = ROOT.'/'.str_replace('\\', '/', $class).'.php')) {
		require_once($file);
	}
}