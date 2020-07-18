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

    $router->get('/reports', 'ReportController@index'); // Get reports
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/register/{matricule}', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
    });

    // Uses Auth Middleware
    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Student Actions from Student Account
        $router->group(['prefix' => 'account/student', 'middleware' => 'is_student'], function () use ($router) {
            $router->put('/', 'StudentAccountController@update'); // Update profile
            $router->get('/results', 'StudentAccountController@getResults'); // Get All results
            $router->get('/fees', 'StudentAccountController@getFees'); // Get fees
            $router->get('/courses/all', 'StudentAccountController@getAllCourses'); // Get all registered courses
            $router->get('/courses/registered', 'StudentAccountController@getRegisteredCourses'); // Get all registered courses
            $router->post('/courses/register', 'StudentAccountController@registerCourses'); // Register courses.
        });

        // Staff actions from Staff account
        $router->group(['prefix' => 'account/staff', 'middleware' => 'is_staff'], function () use ($router) {
            $router->put('/', 'StaffAccountController@update'); // Update profile
            $router->get('/courses', 'StaffAccountController@getCourses'); // Get staff courses and students
            $router->post('/register/marks', 'StaffAccountController@registerMarks'); // record marks
        });

        // Admin actions from Admin account
        $router->group(['prefix' => 'admin', 'middleware' => 'is_admin'], function () use ($router) {
            // Account Management(Update profile)
            $router->put('/', 'AdminController@update'); // Update profile

            // Reports Management
            $router->get('/reports', 'ReportController@index'); // Get reports

            // Students Management
            $router->get('/students', ['uses' => 'StudentController@all']); // Get All students
            $router->post('/students', ['uses' => 'StudentController@create']); // Create a new student
            $router->get('/students/{id}', ['uses' => 'StudentController@show']); // Get a single student
            $router->put('/students/{id}', ['uses' => 'StudentController@update']); // Update a student
            $router->delete('/students/{id}', ['uses' => 'StudentController@destroy']); // Delete a student.

            // Staff Management
            $router->get('/staff', ['uses' => 'StaffController@all']); // Get All staff
            $router->post('/staff', ['uses' => 'StaffController@create']); // Create a new staff
            $router->get('/staff/{id}', ['uses' => 'StaffController@show']); // Get a single staff
            $router->put('/staff/{id}', ['uses' => 'StaffController@update']); // Update staff member
            $router->delete('/staff/{id}', ['uses' => 'StaffController@destroy']); // Delete staff member

            // Faculty Management
            $router->get('/faculties', ['uses' => 'FacultyController@all']); // Get All staff
            $router->post('/faculties', ['uses' => 'FacultyController@create']); // Create a new staff
            $router->get('/faculties/{id}', ['uses' => 'FacultyController@show']); // Get a single faculty
            $router->put('/faculties/{id}', ['uses' => 'FacultyController@update']); // Update a faculty
            $router->delete('/faculties/{id}', ['uses' => 'FacultyController@destroy']); // Delete a faculty

            // Department Management
            $router->get('/departments', ['uses' => 'DepartmentController@all']); // Get All Department
            $router->post('/departments', ['uses' => 'DepartmentController@create']); // Create a new Department
            $router->get('/departments/{id}', ['uses' => 'DepartmentController@show']); // Get a single department
            $router->put('/departments/{id}', ['uses' => 'DepartmentController@update']); // Update a department
            $router->delete('/departments/{id}', ['uses' => 'DepartmentController@destroy']); // Delete a department

            // Course Management
            $router->get('/courses', ['uses' => 'CourseController@all']); // Get All staff
            $router->post('/courses', ['uses' => 'CourseController@create']); // Create a new staff
            $router->get('/courses/{id}', ['uses' => 'CourseController@show']); // Get a single faculty
            $router->put('/courses/{id}', ['uses' => 'CourseController@update']); // Update a faculty
            $router->delete('/courses/{id}', ['uses' => 'CourseController@destroy']); // Delete a faculty

        });

    });
});
