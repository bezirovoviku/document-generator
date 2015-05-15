<?php

Route::get('/', 'HomeController@index');

// dashboard
Route::get('/dashboard', 'DashboardController@index');

// admin
Route::get('/admin', 'AdminController@index');

