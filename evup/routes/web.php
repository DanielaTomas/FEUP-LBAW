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

//User
Route::get('user/{userid}', 'UserController@profile')->name('userProfile')->where(['userid' => '[0-9]+']);
Route::get('user/{id}/edit', 'UserController@showEditForms')->where(['id' => '[0-9]+']);
Route::post('user/{id}/edit', 'UserController@update')->name('editUser')->where(['id' => '[0-9]+']);
//  /user/{id}/requestOrganizer:
//  /api/user/{id}/attended:
//  /api/user/{id}/organized:
//  /search/users:



// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// Admin
Route::get('admin', 'AdminController@show_panel')->name('admin');
Route::get('admin/users', 'AdminController@users');
Route::get('admin/users/search', 'SearchController@searchUsers')->name('users_search');
//Route::get('admin/users/add', 'AdminController@addUser')->name('add_user');           //Waiting for the profile to be finished
Route::get('/users/{id}/view', 'UserController@view')->where(['id' => '[0-9]+'])->name('view_user');
Route::get('admin/users/{id}/edit', 'AdminController@edit')->where(['id' => '[0-9]+'])->name('edit_user');
Route::put('admin/users/{id}/ban', 'AdminController@banUser')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/unban', 'AdminController@unbanUser')->where(['id' => '[0-9]+']);
Route::put('admin/reports/{id}/close', 'AdminController@closeReport')->where(['id' => '[0-9]+']);
Route::put('admin/events/{id}/delete', 'AdminController@cancelEvent')->where(['id' => '[0-9]+']);
Route::post('admin/organizer_requests/{id}/deny', 'AdminController@denyRequest')->where(['id' => '[0-9]+'])->name('organizer_request_deny');
Route::post('admin/organizer_requests/{id}/accept', 'AdminController@acceptRequest')->where(['id' => '[0-9]+'])->name('organizer_request_accept');

//my Events
Route::get('myEvents', 'EventController@userEvents')->name('myEvents');
Route::get('myEvents/organizing', 'EventController@organizerEvents')->name('organizing');
Route::post('myEvents/{id}', 'UserController@leaveEvent')->where(['id' => '[0-9]+']);
Route::get('create', 'EventController@showForms'); 
Route::post('organize', 'EventController@createEvent')->name('createEvent')->where(['id' => '[0-9]+']);
Route::get('event/{id}/attendees', 'EventController@attendees')->where(['id' => '[0-9]+'])->name('attendees');
Route::get('event/{id}/adduser', 'EventController@view_add_user')->where(['id' => '[0-9]+'])->name('view_add_user');
Route::post('event/{eventid}/adduser/{userid}', 'EventController@addUser')->where(['eventid' => '[0-9]+', 'userid' => '[0-9]+'])->name('add_user_event');
Route::post('event/{eventid}/removeuser/{userid}', 'EventController@removeUser')->where(['eventid' => '[0-9]+', 'userid' => '[0-9]+'])->name('remove_user_event');
Route::get('myEvents/createEvent', 'EventController@showForms');
// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Event
Route::get('event/{id}','EventController@show');
Route::post('event/{id}/searchUsers', 'UserController@searchUsers');
Route::post('event/{id}/inviteUsers', 'UserController@inviteUser'); 


