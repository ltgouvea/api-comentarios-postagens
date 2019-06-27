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

    Route::get('users', 'UserController@index')->middleware('permission:read-user');
    Route::post('users', 'UserController@store')->middleware('permission:create-user');
    Route::get('users/{id}', 'UserController@find')->middleware('permission:find-user');
    Route::patch('users/{id}', 'UserController@update')->middleware('permission:update-user');
    Route::delete('users/{id}', 'UserController@delete')->middleware('permission:delete-user');

    Route::get('notifications', 'NotificationController@getNotificationsFromUser');
    Route::get('notifications/unread', 'NotificationController@getUnreadNotificationsFromUser');
    Route::patch('notifications/read/{id}', 'NotificationController@markNotificationAsRead');

    // Postagens
    Route::post('postagens', 'PostController@store');

    // Comentários
    Route::post('postagens/{id}/comentar', 'ComentarioController@store')->middleware(['throttle:3,1', 'permission:comentar-postagem']);
    Route::get('postagens/{id}/comentarios', 'PostController@listarComentariosDaPostagem')->middleware('throttle:20,1');
    Route::get('comentarios_do_usuario', 'UserController@comentariosDoUsuario');
    Route::post('comentarios/{id}/excluir', 'ComentarioController@delete');
});
