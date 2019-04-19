<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UsersController::class , ['except'=>['destroy']]);
    $router->resource('groups','GroupsController',['only'=>['index']]);
    $router->resource('trades','AccountController',['only'=>['index']]);
    $router->resources([
        'system'=>SystemController::class ,
    ]);
});


