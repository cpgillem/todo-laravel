<?php

class UsersController extends \BaseController {

  public function __construct() {
    $this->beforeFilter('auth.basic', ['except' => [
      'create',
      'store'
    ]]);
  }


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    return Redirect::to('/');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
    return View::make('users.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $validator = Validator::make(Input::all(), [
      'email' => 'email|required|unique:users',
      'password' => 'required'
    ]);

    if ($validator->passes()) {
      $user = new User;
      $user->email = Input::get('email');
      $user->password = Hash::make(Input::get('password'));
      $user->save();
      return Redirect::to('/');
    } else {
      return Redirect::back()->withInput()->withErrors($validator->messages());
    }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
    $user = User::find($id);
    return View::make('users.show')->with('user', $user);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
    $user = User::find($id);
    return View::make('users.edit')->with('user', $user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
    $input = Input::all();
    $validator = Validator::make($input, [
      'email' => 'email|required',
      'password' => 'required'
    ]);

    if ($validator->passes()) {
      return Redirect::to('/');
    } else {
      return Redirect::back()->withInput()->withErrors($validator->messages());
    }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
    User::find($id)->delete();
    return Redirect::to('/');
	}


}
