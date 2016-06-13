@extends('layouts.base')

@section('pageTitle', 'Tasks')

@section('content')

<div class="button-group">
  <a class="button" href="/tasks/create">Add Task</a>
  <a class="button" href="/tasks/purge_done">Purge Finished Tasks</a>
</div>

<h3>Task List</h3>

<table>
  <thead>
    <tr>
      <th width="15">#</th>
      <th width="40">Status</th>
      <th width="200">Description</th>
      <th width="200">Due</th>
      <th width="200">Depends On</th>
      <th width="50">Actions</th>
    </tr>
  </thead>

  <tbody>

    @foreach ($tasks as $task)

    <tr>
      <!-- # -->
      <td>
        {{ $task->id }}
      </td>

      <!-- Status -->
      <td>
        @if ($task->done)
          <span class="success label">Done</span>
        @else
          <span class="label">Not Done</span>
        @endif
      </td>

      <!-- Description -->
      <td>
        {{ $task->description }}
      </td>

      <!-- Due -->
      <td>
        @if (isset($task->due))
          Due {{ date('M d, Y \a\t g:i A', strtotime($task->due)) }}
          @if (in_array($task, $overdue) && !$task->done) 
            <span class="alert label">Overdue</span>
          @endif
        @else
          No Due Date
        @endif
      </td>

      <!-- Depends On -->
      <td>
        @if (count($task->dependencies) > 0)

        <ul class="no-bullet">
          @foreach ($task->dependencies as $dependency)
            <li>
              <span class="badge">{{ $dependency->id }}</span> {{ $dependency->description }}
            @if ($dependency->done)
              <span class="success label">Done</span>
            @endif
            </li>
          @endforeach
        </ul>

        @else

        No Dependencies

        @endif

        <div class="tiny button-group">
          {{ link_to_action('TasksController@addDependency', 'Add', ['id' => $task->id], ['class' => 'button']) }}
          {{ link_to_action('TasksController@deleteDependency', 'Delete', ['id' => $task->id], ['class' => 'button']) }}
        </div>
      </td>

      <!-- Actions -->
      <td>
        <div class="expanded small button-group">
          {{ Form::open(['route' => ['tasks.destroy', $task->id], 'method' => 'delete' ]) }}
          {{ link_to_route('tasks.edit', 'Edit', ['id' => $task->id], ['class' => 'button']) }}
          {{ Form::submit('Delete', ['class' => 'button']) }}
          {{ Form::close() }}
        </div>
      </td>
    </tr>

    @endforeach

  </tbody>
</table>

@endsection
