<?php

// models
Route::model('request', 'App\Request');
Route::model('template', 'App\Template');


// home page
Route::get('/', 'HomeController@index');


// ----------------------------------------------------------------------------
// guest only routes
Route::group(['middleware' => 'guest'], function() {
	Route::post('register-login', 'HomeController@loginOrRegister', ['middleware' => 'csrf']);
});


// ----------------------------------------------------------------------------
// user routes
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {

	Route::get('dashboard', 'DashboardController@index');
	Route::get('logout', 'HomeController@logout');
	Route::post('regenerate-api-key', 'DashboardController@regenerateApiKey', ['middleware' => 'csrf']);

	// template resource
	Route::group(['middleware' => 'csrf', 'prefix' => 'template'], function() {
		Route::post('upload', 'DashboardController@uploadTemplate');
		Route::post('{template}/delete', 'DashboardController@deleteTemplate');
	});

	// request resource
	Route::group(['prefix' => 'request/{request}'], function() {
		Route::get('download', 'RequestController@download');
		Route::get('cancel', 'RequestController@cancel');
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
	Route::get('request/{request}', 'ApiController@requestInfo');
});

// ----------------------------------------------------------------------------
// admin only routes
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
	Route::get('users', 'AdminController@users');
	Route::get('requests', 'AdminController@requests');
});
