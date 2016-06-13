<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

/* Task Routes */
Route::get('/tasks/{id}/add_dependency', 'TasksController@addDependency');
Route::post('/tasks/{id}/store_dependency', 'TasksController@storeDependency');
Route::get('/tasks/{id}/delete_dependency', 'TasksController@deleteDependency');
Route::post('/tasks/{id}/destroy_dependency', 'TasksController@destroyDependency');
Route::get('/tasks/purge_done', 'TasksController@purgeDone');

Route::resource('/tasks', 'TasksController', ['before' => 'auth']);

/* User Routes */
Route::resource('/users', 'UsersController', ['before' => 'auth']);

/* Session Routes */
Route::resource('/sessions', 'SessionsController');

/* Aliases */
Route::get('/login', 'SessionsController@create');
Route::get('/logout', 'SessionsController@destroy');
