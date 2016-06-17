<?php

class APITaskController extends \BaseController {

	/**
	 * Display a listing of the resource.
   *
   * GET Request Attributes in order of application to the query:
   *
   *   done: (boolean) Whether to include finished or unfinished tasks. If not
   *         defined, all tasks are returned.
   *
   *   overdue: (boolean) Whether to include overdue or not overdue tasks. If
   *            not defined, all tasks are returned as long as they match other
   *            criteria.
	 *
   *   limit: (integer) Limit the query to a number of items. If not defined,
   *          all tasks are returned.
   *
	 * @return Response
	 */
	public function index()
	{
    $validator = Validator::make(Input::all(), [
      'done' => 'boolean',
      'overdue' => 'boolean',
      'limit' => 'integer'
    ]);

    if ($validator->passes()) {
      $tasks = Auth::user()->tasks();

      if (Input::has('done')) {
        $tasks = $tasks->where('done', '=', Input::get('done'));
      }

      if (Input::has('overdue')) {
        if (Input::get('overdue')) {
          $tasks = $tasks->where('due', '<', DB::raw('NOW()'))->where('due', '!=', 'NULL');
        } else {
          $tasks = $tasks->where('due', '>=', DB::raw('NOW()'));
        }
      }

      if (Input::has('limit')) {
        $tasks = $tasks->take(Input::get('limit'));
      }

      $tasks = $tasks->get();

      return Response::json([
        'error' => false,
        'tasks' => $tasks
      ], 200);
    } else {
      return Response::json([
        'error' => true,
        'message' => 'Invalid data.'
      ], 400);
    }
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
