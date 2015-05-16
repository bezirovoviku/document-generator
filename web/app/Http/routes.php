<?php

// models
Route::model('request', 'App\Request');

// home page
Route::get('/', 'HomeController@index');

// dashboard
Route::get('/dashboard', 'DashboardController@index');

// admin
Route::get('/admin', 'AdminController@index');

// request operations
Route::get('/request/{request}/download', 'RequestController@download');
Route::get('/request/{request}/cancel', 'RequestController@cancel');

