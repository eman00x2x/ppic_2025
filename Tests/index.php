<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Response;
use Pecee\Http\Request;
use EO\Support\Helpers\EnvParser;

use EO\Factories\Factory;
use EO\Model;
use EO\Auth\Auth;
use EO\Database\DBModel;
use EO\Database\DataModel;
use EO\Database\Pagination;
use EO\Database\QueryBuilder;
use EO\Model\PropertyModel as Property;
use EO\Model\AccountModel as Account;
use EO\Model\ArticleModel as Article;
use EO\Services\AuthenticationService;
use EO\Services\AccountService;
use EO\Services\PropertyService;
use EO\Services\LoginService;
use EO\Services\TrafficService;
use EO\Services\LeadService;
use EO\Auth\SessionGuardian;
use EO\Facades\EventFacade;
use EO\Facades\DataModelFacade;
use EO\Facades\LoggerFacade;
use EO\Handlers\EventHandler;
use EO\Handlers\ScheduleHandler;
use EO\Handlers\LoggerHandler;
use EO\EOEngine;
use EO\Http\Controllers\DashboardController;
use EO\Handlers\Tasks\TrafficDBStoreTask;

require_once("../Config/config.php");
require_once("../Config/settings.php");
require_once("../Main/Support/helpers.php");
require_once(ROOT . "/Vendor/autoload.php");
require_once(ROOT . "/Vendor/pecee/simple-router/helpers.php");

new EnvParser( ROOT . "/.env" );

DataModelFacade::setDataModel(new DataModel());
LoggerFacade::setLogger(new LoggerHandler());

$t = new TrafficDBStoreTask();
$s = $t->run();

debug($s);

ob_flush();
flush();