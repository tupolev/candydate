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
$router->get('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@viewUser', 'middleware' => 'auth:api']);
$router->put('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@createUser']);
$router->post('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@editUser', 'middleware' => 'auth:api']);

//user password
$router->post('/api/v1/user/password', ['uses' => '\App\Http\Controllers\UserController@changeUserPassword', 'middleware' => 'auth:api']);

//User email verification
$router->get('/email-verification/{username}/{verification_hash}', [
    'as' => 'emailVerification',
    'uses' => '\App\Http\Controllers\UserController@verifyEmail'
]);

//JobProcess
$router->get('/api/v1/process', ['uses' => '\App\Http\Controllers\JobProcessController@listJobProcesses', 'middleware' => 'auth:api']);
$router->put('/api/v1/process', ['uses' => '\App\Http\Controllers\JobProcessController@createJobProcess', 'middleware' => 'auth:api']);
$router->get('/api/v1/process/{id}', ['uses' => '\App\Http\Controllers\JobProcessController@viewJobProcess', 'middleware' => 'auth:api']);
$router->post('/api/v1/process/{id}', ['uses' => '\App\Http\Controllers\JobProcessController@editJobProcess', 'middleware' => 'auth:api']);
$router->delete('/api/v1/process/{id}', ['uses' => '\App\Http\Controllers\JobProcessController@deleteJobProcess', 'middleware' => 'auth:api']);


//Contacts
