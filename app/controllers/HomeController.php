<?php

use Carbon\Carbon;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

  public function index() {
    if (Auth::check()) {
      $nextTask = Auth::user()->tasks()->where('done', '=', '0')->orderByRaw('-due desc')->first();
      $nextTaskIsOverdue = isset($nextTask) ? Carbon::now()->gt(Carbon::parse($nextTask->due)) : false;
      /* Variables: $nextTask, $agenda, $completeTasks, $incompleteTasks, $overdueTasks */
      return View::make('home.index', [
        'authed' => true,
        'nextTask' => $nextTask,
        'nextTaskIsOverdue' => $nextTaskIsOverdue,
        'agenda' => Auth::user()->tasks()->where('done', '=', '0')->orderByRaw('-due desc')->take(5)->get(),
        'completeTasks' => Auth::user()->tasks()->where('done', '>', '0')->count(),
        'incompleteTasks' => Auth::user()->tasks()->where('done', '=', '0')->count(),
        'overdueTasks' => Auth::user()->tasks()->where('due', '<', 'NOW()')->count()
      ]);
    } else {
      return View::make('home.index', [
        'authed' => false
      ]);
    }
  }
}
