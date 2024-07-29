<?php

namespace Library;

class UserClient
{
    var $user_agent;
    var $browser_name;
    var $browser_version;
    var $platform;
    var $ip;
    var $location;

    private static $_instance = null;

    public static function getInstance () {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    function __construct() {}

    function information() {

        $this->setUserAgent()->setPlatform()->setBrowser()->setIP()->setGeolocation();
        $ip = json_decode($this->ip, true);

        return json_encode(
                array(
                    "ip_address" => $ip['ip'],
                    "user_agent" => $this->user_agent,
                    "browser_name" => $this->browser_name,
                    "browser_version" => $this->browser_version,
                    "platform" => $this->platform,
                    "location" => $this->location
                )
            );
    }

    function setUserAgent() {
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        return $this;
    }

	function getIP() {
        return $this->setIP();
	}

    function setIP() {
        $this->ip = file_get_contents('https://api.ipify.org?format=json');
        return $this;
    }

    function setPlatform() {

        $os_platform = "Unknown Operating System";

        $os_array     = array(
            '/windows nt 11/i'      =>  'Windows 11',
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $os_platform = $value;

        $this->platform = $os_platform;
        return $this;
    }

    function setBrowser() {

        $browser = "Unknown Browser";
        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/edg/i'       => 'Edg',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $browser = $value;


        // finally get the correct version number
        $known = array('Version', $browser, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $this->user_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($this->user_agent,"Version") < strripos($this->user_agent,$browser)){
                if(isset($matches['version'][0])) {
                    $version = $matches['version'][0];
                }else { $version = null; }
            }else {
                if(isset($matches['version'][1])) {
                    $version = $matches['version'][1];
                }else {
                    $version = null;
                }
            }
        }else {
            if(isset($matches['version'][0])) {
                $version = $matches['version'][0];
            }else { $version = null; }
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        $this->browser_name = ($browser == "Edg" ? "Edge" : $browser);
        $this->browser_version = $version;

        return $this;
    }

    function setGeolocation() {
        $response = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$this->ip));
        $this->location = array(
            "continent" => $response['geoplugin_continentName'],
            "timezone" => $response['geoplugin_timezone'],
            "country_name" => $response['geoplugin_countryName'],
            "country_code" => $response['geoplugin_countryCode'],
            "region_name" => $response['geoplugin_region'],
            "city" => $response['geoplugin_city'],
            "latitude" => $response['geoplugin_latitude'],
            "longitude" => $response['geoplugin_longitude'],
            "location_accuracy_radius" => $response['geoplugin_locationAccuracyRadius']
        );

        return $this;
    }

    function getInfo() {

        

    }

}