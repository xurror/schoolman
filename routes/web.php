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
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
    });

    // Uses Auth Middleware
    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Student Actions from Student Account
        $router->group(['prefix' => 'account/student', 'middleware' => 'is_student'], function () use ($router) {
            $router->put('/', 'StudentAccountController@update');
            $router->get('/courses', 'StudentAccountController@getRegisteredCourses');
            $router->get('/results', 'StudentAccountController@getResults');
            $router->get('/fees', 'StudentAccountController@getFees');
            $router->post('/courses', 'StudentAccountController@registerCourses');
        });

        // Staff actions from Staff account
        $router->group(['prefix' => 'account/staff', 'middleware' => 'is_staff'], function () use ($router) {
            $router->put('/', 'StaffAccountController@update');
            $router->get('/courses', 'StaffAccountController@getCourses');
            $router->post('/marks', 'StaffAccountController@registerMarks');
        });

        $router->group(['prefix' => 'admin', 'middleware' => 'is_admin'], function () use ($router) {
            // Account Management(Update profile)
            $router->put('/', 'AdminController@update');

            // Students Management
            $router->get('/student', ['uses' => 'StudentController@all']); // Get All students
            $router->post('/student', ['uses' => 'StudentController@create']); // Create a new student
            $router->get('/student/{id}', ['uses' => 'StudentController@show']);
            $router->put('/student/{id}', ['uses' => 'StudentController@update']);
            $router->delete('/student/{id}', ['uses' => 'StudentController@destroy']);

            // Staff Management
            $router->get('/staff', ['uses' => 'StaffController@all']); // Get All staff
            $router->post('/staff', ['uses' => 'StaffController@create']); // Create a new staff
            $router->get('/staff/{id}', ['uses' => 'StaffController@show']);
            $router->put('/staff/{id}', ['uses' => 'StaffController@update']);
            $router->delete('/staff/{id}', ['uses' => 'StaffController@destroy']);

            // Faculty Management
            $router->get('/faculty', ['uses' => 'FacultyController@all']); // Get All staff
            $router->post('/faculty', ['uses' => 'FacultyController@create']); // Create a new staff
            $router->get('/faculty/{id}', ['uses' => 'FacultyController@show']);
            $router->put('/faculty/{id}', ['uses' => 'FacultyController@update']);
            $router->delete('/faculty/{id}', ['uses' => 'FacultyController@destroy']);

            // Department Management
            $router->get('/department', ['uses' => 'DepartmentController@all']); // Get All staff
            $router->post('/department', ['uses' => 'DepartmentController@create']); // Create a new staff
            $router->get('/department/{id}', ['uses' => 'DepartmentController@show']);
            $router->put('/department/{id}', ['uses' => 'DepartmentController@update']);
            $router->delete('/department/{id}', ['uses' => 'DepartmentController@destroy']);
        });

    });
});
