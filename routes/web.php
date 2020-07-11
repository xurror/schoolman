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
            $router->put('/', 'StudentAccountController@update'); // Update profile
            $router->get('/courses', 'StudentAccountController@getRegisteredCourses'); // Get all registered courses
            $router->get('/results', 'StudentAccountController@getResults'); // Get All results
            $router->get('/fees', 'StudentAccountController@getFees'); // Get fees
            $router->post('/courses', 'StudentAccountController@registerCourses'); // Register courses.
        });

        // Staff actions from Staff account
        $router->group(['prefix' => 'account/staff', 'middleware' => 'is_staff'], function () use ($router) {
            $router->put('/', 'StaffAccountController@update'); // Update profile
            $router->get('/courses', 'StaffAccountController@getCourses'); // Get staff courses and students
            $router->post('/marks', 'StaffAccountController@registerMarks'); // record marks
        });

        $router->group(['prefix' => 'admin', 'middleware' => 'is_admin'], function () use ($router) {
            // Account Management(Update profile)
            $router->put('/', 'AdminController@update'); // Update profile

            // Students Management
            $router->get('/student', ['uses' => 'StudentController@all']); // Get All students
            $router->post('/student', ['uses' => 'StudentController@create']); // Create a new student
            $router->get('/student/{id}', ['uses' => 'StudentController@show']); // Get a single student
            $router->put('/student/{id}', ['uses' => 'StudentController@update']); // Update a student
            $router->delete('/student/{id}', ['uses' => 'StudentController@destroy']); // Delete a student.

            // Staff Management
            $router->get('/staff', ['uses' => 'StaffController@all']); // Get All staff
            $router->post('/staff', ['uses' => 'StaffController@create']); // Create a new staff
            $router->get('/staff/{id}', ['uses' => 'StaffController@show']); // Get a single staff
            $router->put('/staff/{id}', ['uses' => 'StaffController@update']); // Update staff member
            $router->delete('/staff/{id}', ['uses' => 'StaffController@destroy']); // Delete staff member

            // Faculty Management
            $router->get('/faculty', ['uses' => 'FacultyController@all']); // Get All staff
            $router->post('/faculty', ['uses' => 'FacultyController@create']); // Create a new staff
            $router->get('/faculty/{id}', ['uses' => 'FacultyController@show']); // Get a single faculty
            $router->put('/faculty/{id}', ['uses' => 'FacultyController@update']); // Update a faculty
            $router->delete('/faculty/{id}', ['uses' => 'FacultyController@destroy']); // Delete a faculty

            // Department Management
            $router->get('/department', ['uses' => 'DepartmentController@all']); // Get All Department
            $router->post('/department', ['uses' => 'DepartmentController@create']); // Create a new Department
            $router->get('/department/{id}', ['uses' => 'DepartmentController@show']); // Get a single department
            $router->put('/department/{id}', ['uses' => 'DepartmentController@update']); // Update a department
            $router->delete('/department/{id}', ['uses' => 'DepartmentController@destroy']); // Delete a department
        });

    });
});
