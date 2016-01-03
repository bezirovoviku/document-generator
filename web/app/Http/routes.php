<?php

use App\Exceptions\ApiException;

// home page
Route::get('/', 'HomeController@index');
Route::get('docs', 'HomeController@docs');

// ----------------------------------------------------------------------------
// guest only routes
Route::group(['middleware' => 'guest'], function() {
	Route::post('register-login', 'HomeController@loginOrRegister', ['middleware' => 'csrf']);
});

// ----------------------------------------------------------------------------
// password reset link request routes
Route::get('password/email', 'PasswordController@getEmail');
Route::post('password/email', 'PasswordController@postEmail');

// ----------------------------------------------------------------------------
// password reset routes
Route::get('password/reset/{token}', 'PasswordController@getReset');
Route::post('password/reset', 'PasswordController@postReset');

// ----------------------------------------------------------------------------
// user routes
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {

	Route::get('dashboard', 'DashboardController@index');
	Route::get('logout', 'HomeController@logout');
	Route::post('regenerate-api-key', 'DashboardController@regenerateApiKey', ['middleware' => 'csrf']);

	// template resource
	Route::group(['middleware' => 'csrf', 'prefix' => 'template'], function() {
		Route::get('{template}/show', 'TemplateController@show');
		Route::post('{template}/request', 'TemplateController@createRequest');
		Route::post('upload', 'TemplateController@uploadTemplate');
		Route::post('{template}/delete', 'TemplateController@deleteTemplate');
	});

	// request resource
	Route::group(['prefix' => 'request/{request}'], function() {
		Route::get('show', 'RequestController@show');
		Route::get('download', 'RequestController@download');
		//Route::get('cancel', 'RequestController@cancel');
	});

	// admin only
	Route::post('update-limits', 'DashboardController@updateLimits', ['middleware' => 'csrf']);

});

// ----------------------------------------------------------------------------
// api routes
Route::group(['middleware' => 'api', 'prefix' => 'api/v1'], function() {

	Route::post('template', 'ApiController@uploadTemplate');
	Route::delete('template/{template}', 'ApiController@deleteTemplate');

	Route::post('request', 'ApiController@createRequest');
	Route::get('request/{request}', 'ApiController@getRequestInfo');
	Route::get('request/{request}/download', 'ApiController@downloadRequest');
});

// ----------------------------------------------------------------------------
// admin only routes
Route::group(['prefix' => 'admin'], function() {
	Route::get('users', 'AdminController@users');
	Route::get('requests', 'AdminController@requests');
});
