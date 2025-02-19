<?php

namespace EO\Http\Controllers\Website;

use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\View;
use EO\Http\BaseController;
use EO\Services\AuthenticationService as AuthenticationService;

class WebMailController extends BaseController
{
	private AuthenticationService $authService;

	function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
		
		$this->authService = new AuthenticationService();
	}

	public function index(string $template_name, string $hex_content): void
	{
		request()->webMail = true;
		
		$this->isValidTemplate($template_name);
		$content = $this->authService->decodeToken($hex_content);

		View::define(
			name: "template",
			path: "/mail/template.php",
			data: [
				'content' => View::import(
					file_path: ROOT . "/Resources/Templates/mail/$template_name.php",
					data: $content
				)
			]
		);

		View::set('/mail/index.php');
	}

	private function isValidTemplate(string $template_name): bool
	{
		$templatePath = ROOT . "/Resources/Templates/mail/$template_name.php";

		if (!file_exists($templatePath)) {
			return false;
		}

		return true;
	}
}