<?php

namespace EO;

require_once(ROOT . '/Config/settings.php');
require_once(ROOT . '/Main/Support/helpers.php');
require_once(ROOT . '/Vendor/pecee/simple-router/helpers.php');

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\Http\Request;
use Config\Settings;
use EO\Database\DBModel;
use EO\Support\Helpers\EnvParser;
use EO\Http\Middleware\CsrfVerifier;
use EO\Model;
use EO\Auth\Auth;
use EO\Auth\SessionGuardian;
use EO\Facades\DataModelFacade;
use EO\Facades\FileSystemFacade;
use EO\Facades\MailerFacade;
use EO\Facades\LoggerFacade;
use EO\Facades\EventFacade;
use EO\Database\DataModel;
use EO\Handlers\EventHandler;
use EO\Handlers\MailerHandler;
use EO\Handlers\LoggerHandler;
use EO\Handlers\FileSystemHandler;
use EO\Handlers\Mailers\PHPMailer;
use EO\Handlers\Mailers\SymfonyMailer;
use EO\View;

class EOEngine
{
	private Router $router;

	public function __construct()
	{
		$this->router = new Router();
	}

	public function start(): void
	{
		$this->bootstrapEngine();

		(new Settings())->initialize();

		MailerFacade::setMailer(new MailerHandler(new PHPMailer()));

		$this->loadCSRFVerifierConfiguration();
		$this->loadRoutes();

		View::initialize();
	}

	public function bootstrapEngine()
	{
		$this->loadEnv();

		$this->setHandler(DataModelFacade::class, 'setDataModel', DataModel::class);
		$this->setHandler(FileSystemFacade::class, 'setFileSystem', FileSystemHandler::class);
		$this->setHandler(LoggerFacade::class, 'setLogger', LoggerHandler::class);
		$this->setHandler(Auth::class, 'setGuard', SessionGuardian::class);
	}

	public function loadCSRFVerifierConfiguration()
	{
		$csrf_verifier = new CsrfVerifier();
		/* $verifier->setIgnore('/transactions/*');
		$verifier->setIgnore(MANAGE_ALIAS.'/xenditPaymentConfirmation'); */

		$this->router->csrfVerifier($csrf_verifier);
	}

	public function loadRoutes()
	{
		require_once(ROOT . '/Routes/routes.php');

		$this->setHandler(EventFacade::class, 'setEvent', EventHandler::class);

		$this->router->enableMultiRouteRendering(false);
		$this->router->start();
	}

	public function loadEnv()
	{
		new EnvParser(ROOT . "/.env");

		define("DOMAIN", $_ENV['DOMAIN']);
		define("CDN", $_ENV['CDN']);
		define("DEVELOPMENT", $_ENV['DEVELOPMENT']);
	}

	private function setHandler($facade, $method, $handler)
	{
		$facade::$method(new $handler());
	}
}