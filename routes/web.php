<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('api')->group(function () {
    // Account Management
    Route::put('/account', 'API\AccountController@update');

    // Students Management
    Route::get('/student', 'API\StudentController@index');
    Route::post('/student', 'API\StudentController@store');
    Route::get('/student/{id}', 'API\StudentController@show');
    Route::put('/student/{id}', 'API\StudentController@update');
    Route::delete('/student/{id}', 'API\StudentController@destroy');

    // Get All Students.
    // Get 
    Route::apiResources([
        'photos' => 'PhotoController',
        'posts' => 'PostController'
    ]);
});
