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
Route::get('users/{user}/pay','UserController@pay')->name('users.pay');
Route::post('form/file_upload', 'RequestController@fileUpload');
Route::middleware('auth')->post('users/{user}/withdraw','UserController@withdraw');
Route::middleware('auth')->get('recs/{user}','UserController@getRecs')->name('users.recs');
Route::get('rank/{user}','UserController@rankPage')->name('users.rank');
Route::get('rank/get/{user}','UserController@getRank')->name('users.getRank');