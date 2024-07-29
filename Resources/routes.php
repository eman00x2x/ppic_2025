<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\Http\Request;
use Main\Middleware\CsrfVerifier;

/* $verifier = new CsrfVerifier();
$verifier->setIgnore("/transactions/*");
$verifier->setIgnore(MANAGE_ALIAS."/xenditPaymentConfirmation"); */

Router::csrfVerifier(new BaseCsrfVerifier());

Router::group(['exceptionHandler' => \Main\Handlers\ExceptionHandler::class], function () {
	Router::group(['namespace' => 'Main\Controllers'], function () {

		Router::group(['prefix' => ADMIN_ALIAS], function () {

			Router::get("/login", 'LoginController@getLoginForm');
			Router::get('/accountActivation', 'LoginController@accountActivation', ['as' => 'accountActivation']);
			Router::get('/resetPassword', 'LoginController@getResetPasswordForm', ['as' => 'resetPassword']);
			Router::get('/forgotPassword', 'LoginController@getForgotPasswordForm', ['as' => 'forgotPassword']);
			Router::get('/passwordResetSuccess', 'LoginController@passwordResetSuccess', ['as' => 'passwordResetSuccess']);

			Router::post('/checkCredentials', 'LoginController@doLogin');
			Router::post('/saveNewPassword', 'LoginController@saveNewPassword');
			Router::post('/sendPasswordResetLink', 'LoginController@sendPasswordResetLink');

			Router::group(['middleware' => \Main\Middleware\AuthenticationMiddleware::class], function () {
	
				/** DASHBOARD ROUTES */
				Router::get('/', 'DashboardController@index', ['as' => 'dashboard']);

				/** ACCOUNTS ROUTES */
				Router::get('/accounts', 'AccountsController@index', ['as' => 'accounts']);
				Router::get('/accounts/add', 'AccountsController@add', ['as' => 'accounts.add']);
				Router::get('/accounts/{id}/edit', 'AccountsController@edit', ['as' => 'accounts.edit'])->where([ 'id' => '[0-9]+' ]);
				Router::get('/accounts/{id}/delete', 'AccountsController@delete', ['as' => 'accounts.delete'])->where([ 'id' => '[0-9]+' ]);

				Router::post('/accounts/save', 'AccountsController@saveNew', ['as' => 'accounts.save.new']);
				Router::post('/accounts/{id}/save', 'AccountsController@save', ['as' => 'accounts.save.update'])->where([ 'id' => '[0-9]+' ]);

				/** PROFILE ROUTES */
				Router::get('/profile/{id}/edit', 'ProfilesController@edit', ['as' => 'profile.edit'])->where([ 'id' => '[0-9]+' ]);

				Router::post('/profile/{id}/save', 'ProfilesController@save', ['as' => 'profile.save.update'])->where([ 'id' => '[0-9]+' ]);
				Router::post('/profile/upload', 'ProfilesController@upload', ['as' => 'profile.upload']);

				/** ORGANIZATIONS ROUTES */
				Router::get('/organizations', 'OrganizationsController@index', ['as' => 'organizations']);
				Router::get('/organizations/add', 'OrganizationsController@add', ['as' => 'organizations.add']);
				Router::get('/organizations/{id}/edit', 'OrganizationsController@edit', ['as' => 'organizations.edit'])->where([ 'id' => '[0-9]+' ]);
				Router::get('/organizations/{id}/delete', 'OrganizationsController@delete', ['as' => 'organizations.delete'])->where([ 'id' => '[0-9]+' ]);
				
				Router::post('/organizations/save', 'OrganizationsController@saveNew', ['as' => 'organizations.save.new']);
				Router::post('/organizations/{id}/save', 'OrganizationsController@save', ['as' => 'organizations.save.update'])->where([ 'id' => '[0-9]+' ]);
				
				/** PREMIUM GROUPS ROUTES */
				Router::get('/premiumgroups', 'PremiumGroupsController@index', ['as' => 'premiumgroups']);
				Router::get('/premiumgroups/add', 'PremiumGroupsController@add', ['as' => 'premiumgroups.add']);
				Router::get('/premiumgroups/{id}/edit', 'PremiumGroupsController@edit', ['as' => 'premiumgroups.edit'])->where([ 'id' => '[0-9]+' ]);
				Router::get('/premiumgroups/{id}/delete', 'PremiumGroupsController@delete', ['as' => 'premiumgroups.delete'])->where([ 'id' => '[0-9]+' ]);
				
				Router::post('/premiumgroups/save', 'PremiumGroupsController@saveNew', ['as' => 'premiumgroups.save.new']);
				Router::post('/premiumgroups/{id}/save', 'PremiumGroupsController@save', ['as' => 'premiumgroups.save.update'])->where([ 'id' => '[0-9]+' ]);

				 /** VIDEOS ROUTES */
				Router::get('/videos', 'VideosController@index', ['as' => 'videos']);
				Router::get('/videos/add', 'VideosController@add', ['as' => 'videos.add']);
				Router::get('/videos/{id}/edit', 'VideosController@edit', ['as' => 'videos.edit'])->where([ 'id' => '[0-9]+' ]);
				Router::get('/videos/{id}/delete', 'VideosController@delete', ['as' => 'videos.delete'])->where([ 'id' => '[0-9]+' ]);
				
				Router::post('/videos/save', 'VideosController@saveNew', ['as' => 'videos.save.new']);
				Router::post('/videos/{id}/save', 'VideosController@save', ['as' => 'videos.save.update'])->where([ 'id' => '[0-9]+' ]);
				
				/** ADMINISTRATION ROUTES */
				Router::get('/administration', 'AdministrationController@index', ['as' => 'administration']);
				Router::get('/administration/dbBackup', 'AdministrationController@backupDatabase', ['as' => 'administration-backupDatabase']);
				Router::get('/administration/downloadBackup', 'AdministrationController@downloadBackup', ['as' => 'administration-downloadBackup']);
				Router::get('/administration/deleteBackup', 'AdministrationController@deleteBackup', ['as' => 'administration-deleteBackup']);

				Router::post('/administration/queryResult', 'AdministrationController@queryResult', ['as' => 'administration-queryResult']);

				/** DEBUGGING */
				Router::get('/debug', 'DebuggerController@debug', ['as' => 'debug']);

			});

		});

		Router::group(['prefix' => WEB_ALIAS], function () {
			Router::get('/', 'WebController@index', ['as' => 'web']);
			Router::get('/about', 'WebController@about', ['as' => 'web.about']);
			Router::get('/contact', 'WebController@contact', ['as' => 'web.contact']);
			Router::get('/buy', 'WebController@buy', ['as' => 'web.buy']);
			Router::get('/articles/{name}', 'WebController@articles', ['as' => 'web.articles'])->where([ 'name' => '[\w\-\=]+' ]);
			Router::get('/listings/{name}/{id}', 'WebController@viewProperty', ['as' => 'web.view.property'])->where([ 'name' => '[\w\-\=]+', 'id' => '[0-9]+' ]);
		});

		/* Router::error(function(Request $request, \Exception $exception) {
			$request->setRewriteCallback('\Main\Controllers\ErrorsController@notFound');
		}); */

	});
});