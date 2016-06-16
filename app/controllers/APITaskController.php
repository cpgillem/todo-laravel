<?php

class APITaskController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    /* Get all the user's tasks. */
    $tasks = Auth::user()->tasks;

    return Response::json([
      'error' => false,
      'tasks' => $tasks->toArray()],
      200
    );
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
    $validator = Validator::make(Input::all(), [
      'description' => 'required',
      'due' => 'sometimes|date',
      'done' => 'sometimes|boolean'
    ]);

    if ($validator->passes()) {
      $task = new Task();
      $task->description = Input::get('description');

      if (Input::has('due')) {
        $task->due = Input::has('due');
      }

      if (Input::has('done')) {
        $task->done = Input::get('done');
      }

      $task->user = Auth::user()->id;

      $task->save();

      return Response::json([
        'error' => false,
        'task' => $task->toJson()
      ], 200);
    } else {
      return Response::json([
        'error' => true,
        'message' => 'Invalid data.'
      ], 400);
    }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Responshttp://hastebin.com/lezotefiji.ple
	 */
	public function show($id)
	{
    /* Locate the task. */
    $task = Task::findOrFail($id);

    /* Verify ownership of task. */
    if ($task->user == Auth::user()->id) {
      return Response::json([
        'error' => false,
        'task' => $task->toJson()
      ], 200);
    } else {
      // Not your task
      return Response::json([
        'error' => true,
        'message' => 'Task not owned by user.'
      ], 403);
    }
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
    /* Locate the task. */
    $task = Task::findOrFail($id);

    /* Create validation. */
    $validator = Validator::make(Input::all(), [
      'due' => 'sometimes|datetime',
      'done' => 'sometimes|boolean'
    ]);

    /* Verify ownership of task. */
    if ($task->user == Auth::user()->id) {
      /* Validate. */
      if ($validator->passes()) {

        if (Input::has('description')) {
          $task->description = Input::get('description');
        }

        if (Input::has('due')) {
          $task->due = Input::get('due');
        }

        if (Input::has('done')) {
          $task->done = Input::get('done');
        }

        $task->save();

        return Response::json([
          'error' => false,
          'task' => $task->toJson()
        ], 200);

      } else {
        /* Data is invalid. */
        return Response::json([
          'error' => true,
          'message' => 'Invalid data.'
        ], 400);
      }
    } else {
      /* Not your task */
      return Response::json([
        'error' => true,
        'message' => 'Task not owned by user.'
      ], 403);
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
    /* Locate the task. */
    $task = Task::findOrFail($id);

    /* Verify ownership of task. */
    if ($task->user == Auth::user()->id) {
      $task->delete();

      return Response::json([
        'error' => false,
        'message' => 'Task deleted.'
      ], 200);

    } else {
      // Not your task
      return Response::json([
        'error' => true,
        'message' => 'Task not owned by user.'
      ], 403);
    }
	}


}
