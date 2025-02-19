<?php

namespace EO\View;

class Message {

	private static $text;
	private static $type;

	private const ALERT = [
		"error" => [
			"title" => "Error!",
			"class" => "alert alert-danger",
			"icon" => "ti ti-alert-triangle",
		],
		"warning" => [
			"title" => "Warning!",
			"class" => "alert alert-warning",
			"icon" => "ti ti-alert-triangle"
		],
		"info" => [
			"title" => "Info!",
			"class" => "alert alert-primary",
			"icon" => "ti ti-bell"
		],
		"success" => [
			"title" => "Done!",
			"class" => "alert alert-success",
			"icon" => "ti ti-check"
		]
	];

	public static function setResponseMessage($text, $type) {
		self::$text = $text;
		self::$type = self::ALERT[$type];

		return new Message;
	}

	public static function getResponse() {
		$html[] = "<div class='message " . self::$type['class'] . " alert-dismissible' id=''>";
			$html[] = "<div class='d-flex'>";
				$html[] = "<div class=''><i class='" . self::$type['icon'] . " me-2' aria-hidden='true'></i></div>";
				$html[] = "<div class=''>";
					$html[] = "<p class='p-0 m-0'>" . self::$type['title'] . " ". self::$text . " </p>";
				$html[] = "</div>";
			$html[] = "</div>";
			$html[] = "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
		$html[] = "</div>";

		return implode("", $html);
	}

}