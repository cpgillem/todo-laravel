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

Route::resource('/tasks', 'TasksController');

/* User Routes */
Route::resource('/users', 'UsersController');

/* Aliases */
Route::get('/login', function() {
  if (Auth::check()) {
    return Redirect::route('tasks.index');
  } else {
    return View::make('sessions.create');
  }
});

Route::post('/login', function() {
  if (Auth::attempt(Input::only('email', 'password'))) {
    Auth::user();
    return Redirect::intended('/tasks');
  } else {
    return Redirect::to('/login')->with('message', 'Could not authenticate.');
  }
});

Route::get('/logout', function() {
  Auth::logout();
  return Redirect::to('/');
});
