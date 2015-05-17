<?php

// models
Route::model('request', 'App\Request');
Route::model('template', 'App\Template');

// home page
Route::get('/', 'HomeController@index');
Route::post('register-login', 'HomeController@loginOrRegister');
Route::get('logout', 'HomeController@logout');

// dashboard
Route::get('dashboard', 'DashboardController@index');
Route::post('regenerate-api-key', 'DashboardController@regenerateApiKey');
Route::post('update-limits', 'DashboardController@updateLimits');
Route::post('template/upload', 'DashboardController@uploadTemplate');
Route::post('template/{template}/delete', 'DashboardController@deleteTemplate');

// admin
Route::get('admin', 'AdminController@index');

// request operations
Route::get('request/{request}/download', 'RequestController@download');
Route::get('request/{request}/cancel', 'RequestController@cancel');

