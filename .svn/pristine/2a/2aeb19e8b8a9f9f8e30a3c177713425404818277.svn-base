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
Auth::routes();
Route::post('/checkinvite','UserController@ajax_check_invite_code')->name('checkinvite');
Route::resource('users', 'UserController', ['only' => ['show', 'update', 'edit']]);
Route::get('/test','TestController@test');
Route::middleware('auth')->get('/','IndexController@index');
Route::get('coin/create/{unique?}/{type?}', 'CoinController@generateAddress');
Route::get('activate/{user}', 'TestController@testActivate');
Route::get('bonus/{user}/{userPath?}', 'TestController@testBonus');
Route::get('children/{user}', 'TestController@testChildren');