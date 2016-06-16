<?php

use Carbon\Carbon;

class TasksController extends \BaseController {

  public function __construct() {
    $this->beforeFilter('auth.basic');
  }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    /* Get all dates, earliest due dates first. Null date values will be last. Finished tasks
     * will be at the bottom no matter what. */

    /* SOURCE FOR -due desc SYNTAX */
    /* http://stackoverflow.com/questions/2051602/mysql-orderby-a-number-nulls-last */
    $tasks = Auth::user()->tasks()->orderByRaw('done, -due desc')->get();

    /* For each task that is overdue, add it to an array of overdue tasks. */
    $overdue = array();
    foreach ($tasks as $task) {
      $now = Carbon::now();
      $task_due = Carbon::parse($task->due);
      if ($now->gt($task_due)) {
        $overdue[] = $task;
      }
    }

    return View::make('tasks.index')->with('tasks', $tasks)->with('overdue', $overdue);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
    return View::make('tasks.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $input = Input::all();
    $validator = Validator::make(Input::all(), [
      'description' => 'required',
      'due' => 'sometimes|date'
    ]);

    if ($validator->passes()) {
      $task = Task::create([
        'description' => Input::get('description'),
        'user' => Auth::user()->id
      ]);

      if (Input::has('due')) {
        $task->due = Input::get('due');
        $task->save();
      }

      return Redirect::route('tasks.index');
    } else {
      return Redirect::back()->withInput()->withErrors($validator->messages());
    }
  }


  public function addDependency($id) {
    $task = Task::findOrFail($id);

    /* Get all tasks belonging to the logged in user except for the one that is currently being edited. */
    $possible_dependencies = Auth::user()->tasks()->where('id', '!=', $id)->lists('description', 'id');

    if (Auth::check() && Auth::user()->id == $task->user) {
      return View::make('tasks.add_dependency')->with('tasks', $possible_dependencies)->with('task', $task);
    }
  }


  public function storeDependency($id) {
    $task = Task::findOrFail($id);
    $validator = Validator::make(Input::all(), [
      /* The dependency must be the id of a task that exists. */
      'dependency' => 'required|exists:tasks,id|unique:dependencies,dependency_id,NULL,id,task_id,' . $id . '|not_in:' . $id
    ]);

    if (Auth::user()->id == $task->user) {
      if ($validator->passes()) {
        $task->dependencies()->attach(Input::get('dependency'));

        /* Make sure all parents of the new dependency are marked as unfinished if the new dependency is unfinished. */
        if (! Task::find(Input::get('dependency'))->done) {
          $task->done = false;
          $task->save();
          $this->unfinishAllParents($task);
        }

        return Redirect::route('tasks.index');
      } else {
        return Redirect::back()->withInput()->withErrors($validator->messages());
      }
    } else {
      return Redirect::route('tasks.index', ['message', 'You are not authorized to edit this task.']);
    }
  }


  public function deleteDependency($id) {
    $task = Task::findOrFail($id);

    /* Find all dependencies of a task and create a list mapping their descriptions to their id's. */
    $dependencies = $task->dependencies()->lists('description', 'dependency_id');

    if (Auth::check()) {
      if (Auth::user()->id == $task->user) {
        return View::make('tasks.delete_dependency')->with('tasks', $dependencies)->with('task', $task);
      } else {
        return Redirect::route('tasks.index')->with('message', 'You are not authorized to delete this dependency.');
      }
    } else {
      return Redirect::action('HomeController@index', ['message', 'Please log in to remove a dependency.']);
    }
  }


  public function destroyDependency($id) {
    $task = Task::findOrFail($id);
    $validator = Validator::make(Input::all(), [
      'dependency' => 'required|exists:tasks,id'
    ]);

    if (Auth::user()->id == $task->user) {
      if ($validator->passes()) {
        $task->dependencies()->detach(Input::get('dependency'));
        return Redirect::route('tasks.index');
      } else {
        return Redirect::back()->withInput()->withErrors($validator->messages());
      }
    } else {
      return Redirect::route('tasks.index')->with('message', 'You are not authorized to delete that dependency.');
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
    $task = Task::findOrFail($id);

    if ($task->id == Auth::user()->id) {
      return View::make('tasks.show')->with('task', $task);
    } else {
      return Redirect::action('HomeController@index', ['message', 'Please log in to see a task.'])->withMessage();
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
    $task = Task::find($id);
    return View::make('tasks.edit')->with('task', $task);
	}


  /** 
   * Update all of a task's dependencies to be finished. This is useful when marking a task as
   * done, which implies that all tasks that it depended on are also done.
   *
   * This function is recursive in that it finishes all the dependencies of the dependencies found,
   * if any.
   *
   * @param Task task
   */
  public function finishAllDependencies($task) {
    $task->dependencies()->where('done', '=', '0')->get()->each(function($dependency) {
      $dependency->done = true;
      $dependency->save();
      $this->finishAllDependencies($dependency);
    });
  }


  /** 
   * Update all the parents of a task (all tasks which depend on the given task) to be unfinished.
   * 
   * This is useful when marking a task as unfinished in order to make sure its parents are no
   * longer finished, since it is a requirement to have this one finished first. 
   * 
   * This function is recursive in that it unfinishes the parents of this parent if any exist.
   *
   * @param Task task
   */
  public function unfinishAllParents($task) {
    $task->parents()->where('done', '>', '0')->get()->each(function($parent) {
      $parent->done = false;
      $parent->save();
      $this->unfinishAllParents($parent);
    });
  }


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
    $task = Task::findOrFail($id);

    $validator = Validator::make(Input::all(), [
      'due' => 'sometimes|date',
      'done' => 'boolean',
    ]);

    /* Make sure the user has permission to edit this task. */
    if (Auth::user()->id == $task->user) {
      if ($validator->passes()) {
        $task->done = Input::get('done');

        if ($task->done) {
          /* If this task is finished, mark all dependencies as done, too. */
          $this->finishAllDependencies($task);
        } else {
          /* Mark all tasks that depend on this one as unfinished. */
          $this->unfinishAllParents($task);
        }

        $task->description = Input::get('description');
        
        if (Input::get('due') == "") {
          $task->due = NULL;
        } else {
          $task->due = Input::get('due');
        }

        $task->save();
        return Redirect::route('tasks.index');
      } else {
        return Redirect::back()->withInput()->withErrors($validator->messages());
      }
    } else {
      return Redirect::route('tasks.index')->with('message', 'You are not authorized to edit this task.');
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
    $task = Task::findOrFail($id);

    if (Auth::user()->id == $task->user) {
      $task->delete();
      return Redirect::route('tasks.index');
    } else {
      return Redirect::route('tasks.index')->with('message', 'You do not have permission to delete this task.');
    }
	}


  /**
   * Remove all tasks by this user that are done.
   */
  public function purgeDone() {
    Auth::user()->tasks()->where('done', '>', '0')->delete();
    return Redirect::route('tasks.index');
  }

}
