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
Route::middleware('auth:api', 'isPlayer')->put('users/{id}', 'UserControllerAPI@updateUser');


//SETTINGS
Route::middleware('auth:api', 'isAdmin')->get('settings', 'ConfigControllerAPI@getPlatformData');
Route::middleware('auth:api', 'isAdmin')->post('settings/update', 'ConfigControllerAPI@update');
Route::middleware('auth:api', 'isAdmin')->post('admin/email/update', 'UserControllerAPI@updateEmail');
Route::middleware('auth:api', 'isAdmin')->post('admin/password/update', 'UserControllerAPI@updatePassword');
Route::middleware('auth:api', 'isAdmin')->post('/admin/upload/avatar', 'UserControllerAPI@updateAvatar');
Route::middleware('auth:api', 'isAdmin')->post('/admin/user/state', 'UserControllerAPI@changeState');
Route::middleware('auth:api', 'isAdmin')->delete('/admin/user/{id}/{reasonToDelete}', 'UserControllerAPI@deletePlayer');

Route::middleware('auth:api', 'isAdmin')->get('/statistics/users', 'StatisticsControllerAPI@getUsersStatistics');
Route::middleware('auth:api', 'isPlayer')->post('/statistics/user', 'StatisticsControllerAPI@userS');
Route::middleware('auth:api', 'isAdmin')->post('/statistics/date', 'StatisticsControllerAPI@getGamesPerDate');
Route::middleware('auth:api', 'isPlayer')->post('/user/upload/avatar', 'UserControllerAPI@updateAvatar');

//DECKS AND CARDS
Route::middleware('auth:api', 'isAdmin')->get('decks', 'DeckControllerAPI@getDecks');
Route::middleware('auth:api', 'isAdmin')->post('newDeck', 'DeckControllerAPI@newDeck');
Route::middleware('auth:api', 'isAdmin')->post('addallcards', 'DeckControllerAPI@addAllCards');
Route::middleware('auth:api', 'isAdmin')->delete('deletedeck/{id}', 'DeckControllerAPI@deleteDeck');
Route::middleware('auth:api', 'isAdmin')->get('getcurrentdeck/{id}', 'DeckControllerAPI@getCurrentDeck');
Route::middleware('auth:api', 'isAdmin')->put('editdeckimg/{id}', 'DeckControllerAPI@editDeckImg');
Route::middleware('auth:api', 'isAdmin')->put('editcard/{id}', 'DeckControllerAPI@editCardImage');
Route::middleware('auth:api', 'isPlayer')->get('deck/random', 'DeckControllerAPI@getRandomDeck');


Route::post('/game/create', 'GameControllerAPI@create');
Route::post('/game/join', 'GameControllerAPI@join');
Route::put('/game/start', 'GameControllerAPI@start');
Route::put('game/results', 'GameControllerAPI@results');
Route::put('/game/renuncia', 'GameControllerAPI@renuncia');
Route::get('/deckname/{id}', 'GameControllerAPI@getDeckName');


