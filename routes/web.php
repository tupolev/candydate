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

//User
$router->get('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@me', 'middleware' => 'auth:api']);
$router->put('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@create']);
$router->post('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@edit', 'middleware' => 'auth:api']);

//User email verification
$router->get('/email-verification/{username}/{verification_hash}', [
    'as' => 'emailVerification',
    'uses' => '\App\Http\Controllers\UserController@verify'
]);

//JobProcess

//Contacts
