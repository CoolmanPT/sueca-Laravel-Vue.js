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



//ROUTE TO LOGIN VUE APP
Route::get('/', 'VueController@login');

//ROUTE TO ADMIN VUE APP
Route::get('/admin', 'VueController@admin');

//ROUTE TO GAME VUE APP
Route::get('/game', 'VueController@game');


