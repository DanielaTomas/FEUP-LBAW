<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home
Route::get('/', 'HomeController@list')->name('home');
Route::get('search','HomeController@searchEvents')->name('search');

// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// Admin
Route::get('admin', 'AdminController@show')->name('admin');
Route::get('admin/users', 'AdminController@users');
//Route::get('admin/users/{id}/view', 'AdminController@view')->where(['id' => '[0-9]+']); Waiting for the profile to be finished
//Route::get('admin/users/{id}/edit', 'AdminController@edit')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/ban', 'AdminController@banUser')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/unban', 'AdminController@unbanUser')->where(['id' => '[0-9]+']);
Route::get('admin/reports', 'AdminController@reports');
Route::put('admin/reports/{id}/close', 'AdminController@closeReport')->where(['id' => '[0-9]+']);
Route::get('admin/organizer_requests', 'AdminController@organizer_requests');
Route::put('admin/organizer_requests/{id}/close', 'AdminController@closeRequest')->where(['id' => '[0-9]+']);
Route::put('admin/organizer_requests/{id}/accept', 'AdminController@acceptRequest')->where(['id' => '[0-9]+']);

//my Events
Route::get('myEvents', 'EventController@userEvents')->name('myEvents');
Route::post('myEvents/{eventid}', 'UserController@leaveEvent');
Route::post('event/{id}/inviteUsers', 'UserController@inviteUser'); 

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Event
Route::get('event/{id}','EventController@show');
Route::post('event/{id}/searchUsers', 'UserController@searchUsers');


