<?php

/** @var $router \Laravel\Lumen\Routing\Router */
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//Home
$router->get('/', static function () use ($router) {
    return $router->app->version();
});

//Auth
$router->get('/api/v1/test', ['uses' => '\App\Http\Controllers\TestController@index', 'middleware' => 'auth:api']);

//User
$router->get('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@me', 'middleware' => 'auth:api']);
$router->put('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@create', 'middleware' => 'auth:api']);
$router->post('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@edit', 'middleware' => 'auth:api']);


//JobProcess

//Contacts
