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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//LOGIN RELATED
Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');
Route::post('password/email', 'LoginControllerAPI@sendResetLinkEmail');
Route::post('password/reset', 'LoginControllerAPI@resetPassword');

//STATISTICS
Route::get('statistics', 'StatisticsControllerAPI@statistics');

//USER CRUD
Route::post('register', 'UserControllerAPI@store');
Route::post('activate', 'UserControllerAPI@activate');

Route::middleware('auth:api')->get('users', 'UserControllerAPI@getUsers');
Route::middleware('auth:api','isAdmin')->get('blockedusers', 'UserControllerAPI@getBlockedUsers');
Route::middleware('auth:api', 'isAdmin')->get('newusers', 'UserControllerAPI@getNewUsers');
Route::middleware('auth:api', 'isAdmin')->get('settings', 'ConfigControllerAPI@getPlatformData');
Route::middleware('auth:api', 'isAdmin')->post('settings/update', 'ConfigControllerAPI@update');
Route::middleware('auth:api', 'isAdmin')->post('admin/email/update', 'UserControllerAPI@updateEmail');
Route::middleware('auth:api', 'isAdmin')->post('admin/password/update', 'UserControllerAPI@updatePassword');
Route::middleware('auth:api', 'isAdmin')->post('/admin/upload/avatar', 'UserControllerAPI@updateAvatar');
Route::middleware('auth:api', 'isAdmin')->post('/admin/user/state', 'UserControllerAPI@changeState');
Route::middleware('auth:api', 'isAdmin')->delete('/admin/user/{id}/{reasonToDelete}', 'UserControllerAPI@deletePlayer');

Route::middleware('auth:api', 'isAdmin')->get('decks', 'DeckControllerAPI@getDecks');



