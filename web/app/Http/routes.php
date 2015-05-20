<?php

// models
Route::model('request', 'App\Request');
Route::model('template', 'App\Template');


// home page
Route::get('/', 'HomeController@index');
Route::get('docs', 'DocsController@index');


// ----------------------------------------------------------------------------
// guest only routes
Route::group(['middleware' => 'guest'], function() {
	Route::post('register-login', 'HomeController@loginOrRegister');
});


// ----------------------------------------------------------------------------
// user routes
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {

	Route::get('dashboard', 'DashboardController@index');
	Route::post('regenerate-api-key', 'DashboardController@regenerateApiKey');
	Route::get('logout', 'HomeController@logout');

	// template resource
	Route::group(['prefix' => 'template'], function() {
		Route::post('upload', 'DashboardController@uploadTemplate');
		Route::post('{template}/delete', 'DashboardController@deleteTemplate');
	});

	// request resource
	Route::group(['prefix' => 'request/{request}'], function() {
		Route::get('download', 'RequestController@download');
		Route::get('cancel', 'RequestController@cancel');
	});

	// admin only
	Route::post('update-limits', 'DashboardController@updateLimits');

});

// ----------------------------------------------------------------------------
// api routes
Route::group(['middleware' => 'auth', 'prefix' => 'api/v1'], function() {
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
