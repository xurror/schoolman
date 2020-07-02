<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Uses Auth Middleware
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    // Account Management
    $router->put('/account', ['uses' => 'AccountController@update']);

    // Students Management
    $router->get('/student', ['uses' => 'StudentController@index']);
    $router->post('/student', ['uses' => 'StudentController@store']);
    $router->get('/student/{id}', ['uses' => 'StudentController@show']);
    $router->put('/student/{id}', ['uses' => 'StudentController@update']);
    $router->delete('/student/{id}', ['uses' => 'StudentController@destroy']);

    $router->get('/', function () {
        // Uses Auth Middleware
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
    });
});
