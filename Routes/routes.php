<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use EO\Http\Middleware\AccessControlMiddleware;
use EO\Http\Middleware\AuthenticationMiddleware;
use EO\Http\Middleware\TwoFactorAuthenticationMiddleware;
use EO\Http\Middleware\MaintenanceMiddleware;
use EO\Http\Middleware\RateLimiterMiddleware;
use EO\Handlers\ExceptionHandler;

Router::group(['namespace' => 'EO\Http\Controllers'], function () {
	Router::group(['exceptionHandler' => ExceptionHandler::class, 'middleware' => RateLimiterMiddleware::class], function () {

		Router::group(['middleware' => [AuthenticationMiddleware::class, AccessControlMiddleware::class]], function () {

			Router::group(['middleware' => MaintenanceMiddleware::class], function () {
				/** CHARTS ROUTES */
				Router::get('/getTotalAccountsPerStatus/{accountId}', 'ChartsController@getTotalAccountsPerStatus', ['as' => 'getTotalAccountsPerStatus'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalLoginPerDay/{accountId}', 'ChartsController@getTotalLoginPerDay', ['as' => 'getTotalLoginPerDay'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalPropertiesPerCategory/{accountId}', 'ChartsController@getTotalPropertiesPerCategory', ['as' => 'getTotalPropertiesPerCategory'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getMonthlyPostings/{accountId}', 'ChartsController@getMonthlyPostings', ['as' => 'getMonthlyPostings'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalPropertiesPerStatus/{accountId}', 'ChartsController@getTotalPropertiesPerStatus', ['as' => 'getTotalPropertiesPerStatus'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalPropertiesPerListingType/{accountId}', 'ChartsController@getTotalPropertiesPerListingType', ['as' => 'getTotalPropertiesPerListingType'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalTrafficsPerDay/{accountId}', 'ChartsController@getTotalTrafficsPerDay', ['as' => 'getTotalTrafficsPerDay'])->where([ 'accountId' => '[0-9\w\-]+' ]);
				Router::get('/getTotalLeadsPerDay/{accountId}', 'ChartsController@getTotalLeadsPerDay', ['as' => 'getTotalLeadsPerDay'])->where([ 'accountId' => '[0-9\w\-]+' ]);

				/** DASHBOARD ROUTES */
				Router::group(['prefix' => '/dashboard'], function () {
					Router::get('/', 'DashboardController@index', ['as' => 'dashboard']);
				});

				/** ACCOUNTS ROUTES */
				Router::group(['prefix' => '/accounts'], function () {
					Router::get('/', 'AccountsController@index', ['as' => 'accounts']);
					Router::get('/add', 'AccountsController@add', ['as' => 'accounts.add']);
					Router::get('/{id}', 'AccountsController@view', ['as' => 'accounts.view'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/{id}/edit', 'AccountsController@edit', ['as' => 'accounts.edit'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/{id}/delete', 'AccountsController@delete', ['as' => 'accounts.delete'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/exportAccounts', 'AccountsController@exportAccounts', ['as' => 'accounts.export']);

					Router::post('/upload', 'AccountsController@upload', ['as' => 'accounts.upload']);
					Router::post('/confirmSelection', 'AccountsController@confirmSelection', ['as' => 'accounts.confirmSelection']);
					Router::post('/setAccountsStatus', 'AccountsController@setAccountsStatus', ['as' => 'accounts.setStatus']);
					Router::post('/save', 'AccountsController@saveNew', ['as' => 'accounts.save.new']);
					Router::post('/{id}/save', 'AccountsController@save', ['as' => 'accounts.save.update'])->where([ 'id' => '[0-9]+' ]);
				});

				/** PROPERTIES ROUTES */
				Router::group(['prefix' => '/properties'], function () {
					Router::get('/', 'PropertiesController@index', ['as' => 'properties']);
					Router::get('/add', 'PropertiesController@add', ['as' => 'properties.add']);
					/* Router::get('/{name}/edit', 'PropertiesController@edit', ['as' => 'properties.edit'])->where([ 'id' => '[\w\-]+' ]); */
					Router::get('/{id}/edit', 'PropertiesController@edit', ['as' => 'properties.edit'])->where([ 'name' => '[0-9]+' ]);
					Router::get('/{id}/delete', 'PropertiesController@delete', ['as' => 'properties.delete'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/{property_id}/removeDocument/{filename}', 'PropertiesController@removeDocument', ['as' => 'properties.remove.document'])->where([ 'id' => '[0-9\w\-\=_]+', 'filename' => '[\w\-\.]+' ]);
					Router::get('/download', 'PropertiesController@download', ['as' => 'properties.download']);

					Router::post('/saveNew', 'PropertiesController@saveNew', ['as' => 'properties.save.new']);
					Router::post('/{id}/save', 'PropertiesController@save', ['as' => 'properties.save.update'])->where([ 'id' => '[0-9]+' ]);
					Router::post('/confirmSelection', 'PropertiesController@confirmSelection', ['as' => 'properties.confirmSelection']);
					Router::post('/updateStatus', 'PropertiesController@updateStatus', ['as' => 'properties.updateStatus']);
					Router::post('/updateCategory', 'PropertiesController@updateCategory', ['as' => 'properties.updateCategory']);
					Router::post('/docs/upload', 'PropertiesController@upload', ['as' => 'properties.docs.upload']);
					
					Router::group(['prefix' => '/images'], function () {
						Router::post('/{id}/delete', 'PropertyImagesController@delete', ['as' => 'properties.image.delete'])->where([ 'id' => '[0-9\w\-\=_]+' ]);
						Router::post('/upload', 'PropertyImagesController@upload', ['as' => 'properties.image.upload'])->where([ 'id' => '[0-9]+' ]);
					});
				});

				/** LEADS ROUTES */
				Router::group(['prefix' => '/leads'], function () {
					Router::get('/', 'LeadsController@index', ['as' => 'leads']);
					Router::get('/add', 'LeadsController@add', ['as' => 'leads.add']);
					Router::get('/{id}', 'LeadsController@view', ['as' => 'leads.view'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/{id}/edit', 'LeadsController@edit', ['as' => 'leads.edit'])->where([ 'id' => '[0-9]+' ]);
					Router::get('/download', 'LeadsController@download', ['as' => 'leads.download']);

					Router::post('/save', 'LeadsController@saveNew', ['as' => 'leads.save.new']);
					Router::post('/{id}/save', 'LeadsController@save', ['as' => 'leads.save.update'])->where([ 'id' => '[0-9]+' ]);
					Router::post('/delete', 'LeadsController@delete', ['as' => 'leads.delete']);
					Router::post('/updateSource', 'LeadsController@updateSource', ['as' => 'leads.updateSource']);
					Router::post('/updateGroup', 'LeadsController@updateGroup', ['as' => 'leads.updateGroup']);
					Router::post('/confirmSelection', 'LeadsController@confirmSelection', ['as' => 'leads.confirmSelection']);

					/** LEAD GROUPS ROUTES */
					Router::group(['prefix' => '/groups'], function () {
						Router::get('/', 'LeadGroupsController@index', ['as' => 'leads.groups']);
						Router::get('/add', 'LeadGroupsController@add', ['as' => 'leads.groups.add']);
						Router::get('/{id}/edit', 'LeadGroupsController@edit', ['as' => 'leads.groups.edit'])->where([ 'id' => '[0-9]+' ]);
						/* Router::get('/{id}/delete', 'LeadGroupsController@delete', ['as' => 'leads.groups.delete'])->where([ 'id' => '[0-9]+' ]); */

						Router::post('/save', 'LeadGroupsController@saveNew', ['as' => 'leads.groups.save.new']);
						Router::post('/{id}/save', 'LeadGroupsController@save', ['as' => 'leads.groups.save.update'])->where([ 'id' => '[0-9]+' ]);
						Router::post('/delete', 'LeadGroupsController@delete', ['as' => 'leads.groups.delete']);
						Router::post('/confirmSelection', 'LeadGroupsController@confirmSelection', ['as' => 'leads.groups.confirmSelection']);
					});
				});

				/** ARTICLES ROUTES */
				Router::group(['prefix' => '/web-articles'], function () {
					Router::get('/', 'ArticlesController@index', ['as' => 'articles']);
					Router::get('/add', 'ArticlesController@add', ['as' => 'articles.add']);
					Router::get('/{name}/edit', 'ArticlesController@edit', ['as' => 'articles.edit'])->where([ 'name' => '[\w\-]+' ]);
					
					Router::post('/changeCategory', 'ArticlesController@changeCategory', ['as' => 'articles.changeCategory']);
					Router::post('/confirmSelection', 'ArticlesController@confirmSelection', ['as' => 'articles.confirmSelection']);
					Router::post('/setPublishStatus', 'ArticlesController@setPublishStatus', ['as' => 'articles.setPublishStatus']);
					Router::post('/delete', 'ArticlesController@delete', ['as' => 'articles.delete']);
					Router::post('/save', 'ArticlesController@saveNew', ['as' => 'articles.save.new']);
					Router::post('/{id}/save', 'ArticlesController@save', ['as' => 'articles.save.update'])->where([ 'id' => '[0-9]+' ]);
					Router::post('/upload', 'ArticlesController@upload', ['as' => 'articles.upload']);
				});

				/** VIDEOS ROUTES */
				Router::group(['prefix' => '/web-videos'], function () {
					Router::get('/', 'VideosController@index', ['as' => 'videos']);
					Router::get('/add', 'VideosController@add', ['as' => 'add-videos']);
					Router::get('/{id}/edit', 'VideosController@edit', ['as' => 'videos.edit'])->where([ 'id' => '[\w\-]+' ]);
					
					Router::post('/changeCategory', 'VideosController@changeCategory', ['as' => 'videos.changeCategory']);
					Router::post('/confirmSelection', 'VideosController@confirmSelection', ['as' => 'videos.confirmSelection']);
					Router::post('/delete', 'VideosController@delete', ['as' => 'videos.delete']);
					Router::post('/save', 'VideosController@saveNew', ['as' => 'videos.save.new']);
					Router::post('/{id}/save', 'VideosController@save', ['as' => 'videos.save.update'])->where([ 'id' => '[0-9]+' ]);
				});

				/** LOGINS ROUTES */
				Router::group(['prefix' => '/logins'], function () {
					Router::post('/confirmSelection', 'LoginController@confirmSelection', ['as' => 'logins.confirmSelection']);
					Router::post('/delete', 'LoginController@delete', ['as' => 'logins.delete']);
				});

				/** TRAFFICS ROUTES */
				Router::group(['prefix' => '/traffics'], function () {
					Router::get('/', 'TrafficsController@index', ['as' => 'traffics']);
				});
			});

			/** ADMIN ROUTES */
			Router::group(['prefix' => '/admin'], function () {

				Router::get('/twoFactorAuthentication', 'TwoFactorAuthenticationController@index', ['as' => 'twoFactorAuthentication']);
				Router::get('/twoFactorAuthentication/sendAuthorizationCode', 'TwoFactorAuthenticationController@sendAuthorizationCode', ['as' => 'sendAuthorizationCode']);
				Router::post('/twoFactorAuthentication/verifyCode', 'TwoFactorAuthenticationController@verifyAuthorizationCode', ['as' => 'verifyAuthorizationCode']);

				/* Router::group(['middleware' => TwoFactorAuthenticationMiddleware::class], function () { */
					/** ADMINISTRATION ROUTES */
					Router::group(['prefix' => '/super'], function () {
						Router::get('/', 'AdministrationController@index', ['as' => 'administration']);
						Router::get('/databaseBackupFiles', 'AdministrationController@databaseBackupFiles', ['as' => 'administration.databaseBackupFiles']);
						Router::get('/dbBackup', 'AdministrationController@backupDatabase', ['as' => 'administration.backupDatabase']);
						Router::get('/downloadBackup', 'AdministrationController@downloadBackup', ['as' => 'administration.downloadBackup']);
						Router::get('/deleteBackup', 'AdministrationController@deleteBackup', ['as' => 'administration.deleteBackup']);
						Router::get('/restoreBackup', 'AdministrationController@restoreBackup', ['as' => 'administration.restoreBackup']);
						Router::get('/cron', 'AdministrationController@cronTasks', ['as' => 'administration.cronTasks']);
						Router::get('/cron/run/{task}', 'AdministrationController@cronTaskRun', ['as' => 'administration.cronTaskRun'])->where([ 'task' => '[\w\-\=]+' ]);
						
						Router::post('/queryResult', 'AdministrationController@queryResult', ['as' => 'administration.queryResult']);

						Router::get('/logs', 'LogsController@index', ['as' => 'logs']);

						Router::post('/logs/confirmSelection', 'LogsController@confirmSelection', ['as' => 'logs.confirmSelection']);
						Router::post('/logs/delete', 'LogsController@deleteMultiple', ['as' => 'logs.deleteMultiple']);

						/** SETTINGS ROUTES */
						Router::group(['prefix' => '/settings'], function () {
							Router::get('/{page}', 'SettingsController@index', ['as' => 'settings'])->where([ 'page' => '[\w\-\=]+' ]);
						});
					});
				/* }); */

				Router::get('/web-settings/{page}', 'SettingsController@webSettings', ['as' => 'webSettings'])->where([ 'page' => '[\w\-\=]+' ]);
				Router::group(['prefix' => '/settings'], function () {
					Router::post('/save', 'SettingsController@save', ['as' => 'settings']);
				});

				/** TRAFFICS ROUTES */
				Router::group(['prefix' => '/traffics'], function () {
					Router::post('/confirmSelection', 'TrafficsController@confirmSelection', ['as' => 'traffics.confirmSelection']);
					Router::post('/delete', 'TrafficsController@deleteMultiple', ['as' => 'traffics.deleteMultiple']);
				});
			});


		});

		Router::group(['middleware' => MaintenanceMiddleware::class], function () {
			/** WEBSITE */
			Router::group(['namespace' => 'Website'], function () {
				Router::get('/', 'HomeController@index', ['as' => 'web']);
				Router::get('/about', 'PagesController@about', ['as' => 'web.about']);
				Router::get('/contact', 'PagesController@contact', ['as' => 'web.contact']);
				Router::get('/terms', 'PagesController@terms', ['as' => 'web.terms']);
				Router::get('/privacy', 'PagesController@privacy', ['as' => 'web.privacy']);
				Router::get('/videos', 'VideosController@index', ['as' => 'web.videos']);

				Router::group(['prefix' => '/articles'], function () {
					Router::get('/', 'ArticlesController@articles', ['as' => 'web.articles']);
					Router::get('/{name}/{id}', 'ArticlesController@getArticle', ['as' => 'web.view.article'])->where([ 'name' => '[\w\-\=]+', 'id' => '[0-9]+' ]);
				});

				Router::group(['prefix' => '/agents'], function () {
					Router::get('/', 'AccountsController@index', ['as' => 'web.agents']);
					Router::get('/{name}/{id}', 'AccountsController@view', ['as' => 'web.view.agents'])->where([ 'name' => '[\w\-\=]+', 'id' => '[0-9]+' ]);
				});

				Router::get('/buy', 'PropertiesController@index', ['as' => 'web.properties.buy']);
				Router::get('/rent', 'PropertiesController@index', ['as' => 'web.properties.rent']);

				Router::group(['prefix' => '/listings'], function () {
					
					/* Router::get('/{listing_type}', 'PropertiesController@index', ['as' => 'web.properties'])->where([ 'listing_type' => '[\w\-\=]+' ]); */
					Router::get('/related', 'PropertiesController@relatedProperties', ['as' => 'web.related.properties']);
					Router::get('/{name}/{id}', 'PropertiesController@viewProperty', ['as' => 'web.view.property'])->where([ 'name' => '[\w\-\=]+', 'id' => '[0-9]+' ]);
					
					Router::post('/leads/new', 'LeadsController@saveLeads', ['as' => 'web.save.leads']);
				});

				Router::post('/saveTraffic', 'TrafficsController@saveNew', ['as' => 'web.new.traffics']);

				Router::group(['prefix' => '/mail'], function () {
					Router::get('/{template}/{content}', 'WebMailController@index', ['as' => 'web.mail'])->where([ 'template' => '[\w\-]+', 'content' => '[\w\-]+' ]);
				});
			});

			/** REGISTRATION 
			Router::group(['prefix' => '/registration'], function () {
				Router::get("/", 'RegistrationController@registrationForm', ['as' => 'registration']);
				Router::get("/success", 'RegistrationController@successPage', ['as' => 'registration.success']);
				Router::get('/accountActivation', 'RegistrationController@accountActivation', ['as' => 'registration.accountActivation']);
				Router::get("/resendEmailActivationLink", 'RegistrationController@resendActivationEmailForm', ['as' => 'registration.resendActivationEmailForm']);

				Router::post("/storeUserRegistration", 'RegistrationController@storeUserRegistration', ['as' => 'registration.save']);
				Router::post("/resendActivationEmail", 'RegistrationController@resendActivationEmail', ['as' => 'registration.resendActivationEmail']);
			});*/
		});

		/** AUTHENTICATION */
		Router::group(['prefix' => '/login'], function () {
			Router::get("/", 'AuthenticationController@getLoginForm', ['as' => 'login']);
			Router::get('/resetPassword', 'AuthenticationController@getResetPasswordForm', ['as' => 'resetPassword']);
			Router::get('/requestPasswordReset', 'AuthenticationController@getRequestPasswordResetForm', ['as' => 'requestPasswordReset']);
			Router::get('/passwordResetSuccess', 'AuthenticationController@passwordResetSuccess', ['as' => 'passwordResetSuccess']);

			Router::post('/checkCredentials', 'AuthenticationController@doLogin', ['as' => 'login.do']);
			Router::post('/saveNewPassword', 'AuthenticationController@saveNewPassword', ['as' => 'login.saveNewPassword']);
			Router::post('/sendPasswordResetLink', 'AuthenticationController@sendPasswordResetLink', ['as' => 'login.sendPasswordResetLink']);
		});

	});
});