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

// API route group
$router->group(['prefix' => 'auth'], function () use ($router) {
    // $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

 });

// Uses Auth Middleware
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    // Account Management(Update profile)
    $router->put('/account', 'AccountController@update');

    // Students Management
    $router->get('/student', ['uses' => 'StudentController@allstudents']); // Get All students
    $router->post('/student', ['uses' => 'StudentController@create']); // Create a new student
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
