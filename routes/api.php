<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@create');
Route::patch('users/password/reset', 'UserController@requestPasswordReset');
Route::patch('users/password/change', 'UserController@changeUserPassword');

Route::middleware('auth:api')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::get('users', 'UserController@index');
    Route::get('users/{id}', 'UserController@find');

    Route::get('notifications', 'NotificationController@getNotificationsFromUser');
    Route::get('notifications/unread', 'NotificationController@getUnreadNotificationsFromUser');
    Route::patch('notifications/read/{id}', 'NotificationController@markNotificationAsRead');
});
