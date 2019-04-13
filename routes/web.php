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

Route::get('/', 'PagesController@root')->name('root');
Auth::routes();
Route::get('/checkinvite','InvitecodesController@ajax_check_invite_code')->name('checkinvite');
Route::resource('users', 'UserController', ['only' => ['show', 'update', 'edit']]);
Route::resource('systems','SystemController');

