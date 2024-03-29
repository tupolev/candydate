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
$router->get('/', function () use ($router) {
    return 'Candydate API v1';
});

//User
$router->get('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@viewUser', 'middleware' => ['auth:api']]);
$router->put('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@createUser']);
$router->post('/api/v1/user', ['uses' => '\App\Http\Controllers\UserController@editUser', 'middleware' => ['auth:api']]);

//user password
$router->post('/api/v1/user/password', ['uses' => '\App\Http\Controllers\UserController@changeUserPassword', 'middleware' => ['auth:api']]);

//User email verification
$router->get('/email-verification/{username}/{verification_hash}', [
    'as' => 'emailVerification',
    'uses' => '\App\Http\Controllers\UserController@verifyEmail',
]);

//JobProcess
$router->get('/api/v1/process', ['uses' => '\App\Http\Controllers\JobProcessController@listJobProcesses', 'middleware' => 'auth:api']);
$router->put('/api/v1/process', ['uses' => '\App\Http\Controllers\JobProcessController@createJobProcess', 'middleware' => 'auth:api']);
$router->get('/api/v1/process/{jobProcessId}', ['uses' => '\App\Http\Controllers\JobProcessController@viewJobProcess', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->post('/api/v1/process/{jobProcessId}', ['uses' => '\App\Http\Controllers\JobProcessController@editJobProcess', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->delete('/api/v1/process/{jobProcessId}', ['uses' => '\App\Http\Controllers\JobProcessController@deleteJobProcess', 'middleware' => ['auth:api', 'is_owner_job_process']]);

//JobProcessLog
$router->get('/api/v1/process/{jobProcessId}/log', ['uses' => '\App\Http\Controllers\JobProcessLogController@getJobProcessLog', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->put('/api/v1/process/{jobProcessId}/log', ['uses' => '\App\Http\Controllers\JobProcessLogController@createJobProcessLogEntry', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->get('/api/v1/process/{jobProcessId}/log/{jobProcessLogId}', ['uses' => '\App\Http\Controllers\JobProcessLogController@getJobProcessLogEntry', 'middleware' => ['auth:api', 'is_owner_job_process']]);

//JobProcessStatus
$router->post('/api/v1/process/{jobProcessId}/status', ['uses' => '\App\Http\Controllers\JobProcessStatusController@changeJobProcessStatus', 'middleware' => ['auth:api', 'is_owner_job_process']]);

//Contacts
$router->get('/api/v1/process/{jobProcessId}/contacts', ['uses' => '\App\Http\Controllers\JobProcessContactController@listJobProcessContacts', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->put('/api/v1/process/{jobProcessId}/contacts', ['uses' => '\App\Http\Controllers\JobProcessContactController@createJobProcessContact', 'middleware' => ['auth:api', 'is_owner_job_process']]);
$router->post('/api/v1/process/{jobProcessId}/contacts/{jobProcessContactId}', ['uses' => '\App\Http\Controllers\JobProcessContactController@editJobProcessContact', 'middleware' => ['auth:api', 'is_owner_job_process', 'is_owner_job_process_contact']]);
$router->get('/api/v1/process/{jobProcessId}/contacts/{jobProcessContactId}', ['uses' => '\App\Http\Controllers\JobProcessContactController@viewJobProcessContact', 'middleware' => ['auth:api', 'is_owner_job_process', 'is_owner_job_process_contact']]);
$router->delete('/api/v1/process/{jobProcessId}/contacts/{jobProcessContactId}', ['uses' => '\App\Http\Controllers\JobProcessContactController@deleteJobProcessContact', 'middleware' => ['auth:api', 'is_owner_job_process', 'is_owner_job_process_contact']]);
