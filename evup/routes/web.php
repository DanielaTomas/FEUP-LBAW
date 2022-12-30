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
use App\Http\Controllers\UploadController;
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
Route::get('/users/{id}/view', 'UserController@view')->where(['id' => '[0-9]+'])->name('view_user');
Route::put('admin/users/{id}/delete', 'AdminController@deleteUser')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/ban', 'AdminController@banUser')->where(['id' => '[0-9]+']);
Route::put('admin/users/{id}/unban', 'AdminController@unbanUser')->where(['id' => '[0-9]+']);
Route::put('admin/reports/{id}/close', 'AdminController@closeReport')->where(['id' => '[0-9]+']);
Route::put('admin/events/{id}/delete', 'AdminController@cancelEvent')->where(['id' => '[0-9]+']);

//my Events
 
 Route::post('api/myEvents/leave_event', 'UserController@leaveEvent');
 Route::get('api/myEvents/organizing', 'EventController@organizerEvents');
 Route::post('api/myEvents/onMyAgenda', 'EventController@myEvents');

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
Route::get('appeal/{id}', 'AppealController@getAppeal')->where(['id' => '[0-9]+'])->name('appeal');
Route::post('appeal/{id}', 'AppealController@saveAppeal')->where(['id' => '[0-9]+'])->name('appeal_save');

// Event

Route::post('event/{id}/delete/{commentid}', 'CommentController@deleteComment')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('delete_comment'); 
Route::post('event/{id}/createComment/{parentid?}', 'CommentController@createComment')->where(['id' => '[0-9]+'])->name('create_comment'); 
Route::post('event/{id}/editComment/{commentid}/update', 'CommentController@update')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('update_comment');
Route::post('event/{id}/editComment/{commentid}', 'CommentController@edit')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('edit_comment');
Route::post('api/requestToJoin', 'UserController@requestToJoin');

Route::post('event/{id}/like/{commentid}/voted/{voted}','CommentController@like')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('like');
Route::post('event/{id}/dislike/{commentid}/voted/{voted}','CommentController@dislike')->where(['id' => '[0-9]+', 'commentid' => '[0-9]+'])->name('dislike');;

//Filter
Route::post('api/filter_tag', 'HomeController@filterTag');
Route::post('api/filter_category', 'HomeController@filterCategory');

Route::get('/upload', 'UploadController@create');
Route::post('/upload', 'UploadController@store');
