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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@list')->name('home');
Route::get('search','HomeController@searchEvents')->name('search');

// Static Pages
Route::get('aboutUs', 'StaticPagesController@getAboutUs')->name('about');
Route::get('contactUs', 'StaticPagesController@getContactUs')->name('contact');
Route::post('contactUs', 'StaticPagesController@saveContact')->name('contact_save');
Route::get('faq', 'StaticPagesController@getFaq')->name('faq');


//User
Route::get('user/{id}/public', 'UserController@viewUser')->name('publicProfile')->where(['id' => '[0-9]+']);
Route::get('user/{userid}', 'UserController@profile')->name('userProfile')->where(['userid' => '[0-9]+']);
Route::get('user/{id}/edit', 'UserController@showEditForms')->name('edit_user')->where(['id' => '[0-9]+']);
Route::get('/user/{id}/organizerRequest', 'UserController@organizerRequest')->where(['id' => '[0-9]+'])->name('request_organizer');
Route::post('user/{id}/edit', 'UserController@update')->name('editUser')->where(['id' => '[0-9]+']);
Route::post('/user/deny/{id}', 'UserController@denyRequest')->where(['id' => '[0-9]+'])->name('invite_request_deny');
Route::post('/user/accept/{id}', 'UserController@acceptRequest')->where(['id' => '[0-9]+'])->name('invite_request_accept');
Route::post('user/{id}/delete', 'UserController@delete')->where(['id' => '[0-9]+'])->name('delete_user');
//  /user/{id}/requestOrganizer:
//  /api/user/{id}/attended:
//  /api/user/{id}/organized:
//  /search/users:


// Notifications
Route::get('/api/notifications', 'NotificationController@show');
Route::put('notifications', 'NotificationController@readNotifications');
Route::put('notifications/{id}', 'NotificationController@readNotification')->where(['id' => '[0-9]+']);


//Invite



// Admin
Route::get('admin', 'AdminController@show_panel')->name('admin');
Route::get('admin/users', 'AdminController@users');
Route::get('admin/users/search', 'SearchController@searchUsers')->name('users_search');
Route::get('admin/users/add', 'AdminController@addUserAccount')->name('add_user_account');       
Route::post('admin/users/add', 'AdminController@createUser')->name('create_user');    
Route::get('/users/{id}/view', 'UserController@view')->where(['id' => '[0-9]+'])->name('view_user');
Route::put('admin/users/{id}/ban', 'AdminController@banUser')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/unban', 'AdminController@unbanUser')->where(['id' => '[0-9]+']);
Route::put('admin/reports/{id}/close', 'AdminController@closeReport')->where(['id' => '[0-9]+']);
Route::put('admin/events/{id}/delete', 'AdminController@cancelEvent')->where(['id' => '[0-9]+']);
Route::put('admin/organizer_requests/{id}/deny', 'AdminController@denyRequest')->where(['id' => '[0-9]+'])->name('organizer_request_deny');
Route::put('admin/organizer_requests/{id}/accept', 'AdminController@acceptRequest')->where(['id' => '[0-9]+'])->name('organizer_request_accept');

//my Events
Route::get('myEvents', 'EventController@userEvents')->name('myEvents');
Route::get('myEvents/organizing', 'EventController@organizerEvents')->name('organizing');
Route::get('myEvents/createEvent', 'EventController@showForms')->name('create_events');
Route::post('myEvents/createEvent', 'EventController@createEvent')->name('createEvent')->where(['id' => '[0-9]+']);
Route::get('event/{id}/attendees', 'EventController@attendees')->where(['id' => '[0-9]+'])->name('attendees');
Route::get('event/{id}/adduser', 'EventController@view_add_user')->where(['id' => '[0-9]+'])->name('view_add_user');
Route::post('event/{eventid}/adduser/{userid}', 'EventController@addUser')->where(['eventid' => '[0-9]+', 'userid' => '[0-9]+'])->name('add_user_event');
Route::post('event/{eventid}/removeuser/{userid}', 'EventController@removeUser')->where(['eventid' => '[0-9]+', 'userid' => '[0-9]+'])->name('remove_user_event');
Route::post('api/myEvents/leave_event', 'UserController@leaveEvent')->where(['id' => '[0-9]+']);

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('forgotPassword', 'Auth\ResetPasswordController@showSendLinkForm')->name('forgot_password');
Route::post('forgot_password', 'Auth\ResetPasswordController@sendLink')->name('send_link');
Route::get('reset', 'Auth\ResetPasswordController@showResetPasswordForm')->name('password.reset');
Route::post('reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Event

Route::get('event/{id}','EventController@show')->name('show_event');
Route::get('event/{id}/edit','EventController@edit')->where(['id' => '[0-9]+'])->name('edit_event');
Route::post('event/{id}/update','EventController@update')->name('update_event');
Route::post('event/{id}/searchUsers', 'UserController@searchUsers');
Route::post('event/{id}/inviteUsers', 'UserController@inviteUser'); 
Route::post('event/{id}/delete/{commentid}', 'CommentController@deleteComment')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('delete_comment'); 
Route::post('event/{id}/create', 'CommentController@create')->where(['id' => '[0-9]+'])->name('create_comment'); 
Route::post('event/{id}/editComment/{commentid}/update', 'CommentController@update')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('update_comment');
Route::post('event/{id}/editComment/{commentid}', 'CommentController@edit')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('edit_comment');
Route::post('api/requestToJoin', 'UserController@requestToJoin');


//Filter
Route::post('api/filter_tag', 'HomeController@filterTag');


