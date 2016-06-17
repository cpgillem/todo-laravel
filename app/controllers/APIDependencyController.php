<?php

class APIDependencyController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
    //
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    if (Input::has('task_id')) {
      $validator = Validator::make(Input::all(), [
        'task_id' => 'required|exists:tasks,id',
        'dependency_id' => 'required|exists:tasks,id|unique:dependencies,dependency_id,NULL,id,task_id,' 
          . Input::get('task_id') 
          . '|not_in:' . Input::get('task_id')
      ]);

      if ($validator->passes()) {
        $task = Task::find(Input::get('task_id'));
        $dependency = Task::find(Input::get('dependency_id'));

        if (Auth::user()->id == $task->user
        && Auth::user()->id == $dependency->user) {
          $task->dependencies()->attach(Input::get('dependency_id'));

          return Response::json([
            'error' => false,
            'resource' => $dependency->toJson()
          ], 200);
        } else {
          return Response::json([
            'error' => true,
            'message' => 'One or both tasks not owned by user.'
          ], 403);
        }
      } else {
        return Response::json([
          'error' => true,
          'message' => 'Invalid data.'
        ], 400);
      }
    } else {
      return Response::json([
        'error' => true,
        'message' => 'No task id specified.'
      ], 400);
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
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
    // TODO: destroy a dependency relationship by its own id, not any task's.
	}


}
