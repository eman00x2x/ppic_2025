<?php

namespace EO;

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Exceptions\InvalidArgumentException;

use EO\View\Message;
use EO\View\Template;

/**
 * View class for handling view related operations.
 */
class View extends Template
{
	/**
	 * Initializes the view system by setting up the necessary routes and templates.
	 * @return void
	 */
	public static function initialize(): void 
	{
		$accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
		if (substr_count($accept_encoding, 'gzip')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}

		$content = self::render();

		if (request()->isAjax()) {
			echo $content;
		} else {
			$template_path = self::getMasterTemplate();

			if (request()->error == 1) {
				$template_path = ROOT . '/Resources/Templates/errors/template.php';
			}

			/* if($template_path == "") {
				throw new InvalidArgumentException("Master template not set!");
			} */

			if(!isset(request()->webMail) && $template_path != "") {
				require_once($template_path);
				echo implode("", $html);
			}
		}
	}

	static function getHtmlResponseMessage(String $message, String $type) 
	{
		return Message::setResponseMessage($message, $type)->getResponse();
	}

}
