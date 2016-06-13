<?php

class SessionsController extends \BaseController {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
    if (Auth::check()) {
      return Redirect::route('tasks.index');
    } else {
      return View::make('sessions.create');
    }
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    if (Auth::attempt(Input::only('email', 'password'))) {
      Auth::user();
      return Redirect::intended('/tasks');
    } else {
      return Redirect::route('sessions.create')->with('message', 'Could not authenticate.');
    }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy()
	{
    Auth::logout();
    return Redirect::action('HomeController@index');
	}

  public function index() {}
  public function show() {}
}
