@extends('layouts.base')

@section('pageTitle', 'Welcome')

@section('content')

  <p class="lead">Welcome to my terribad todo app!</p>
  <p>Some text about what this app does and stuff</p>

  @if ($authed)

  <div class="row">
    <div class="column large-4">
      <h3>Your Mission</h3>
      <h5><span class="badge">{{ $nextTask->id }}</span> {{ $nextTask->description }}</h5>
      <p>

      @if (isset($nextTask->due))
        <strong>Due: </strong>{{ $nextTask->due }}
        
        @if ($nextTaskIsOverdue)
          <span class="alert label">Overdue</span>
        @endif

      @endif

      </p>

      @if (count($nextTask->dependencies) > 0)

      <p>
        <strong>Depends On: </strong>

        @foreach ($nextTask->dependencies as $dependency)

        <span class="badge">{{ $dependency->id }}</span>
        {{ $dependency->description }}
        @if ($dependency->done)
          <span class="success label">Done</span>
        @endif

        @endforeach
      </p>

      @endif
    </div>

    <div class="column large-4">
      <h3>Your Agenda</h3>
      <table>
        <thead>
          <tr>
            <th width="15">#</th>
            <th width="200">Description</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($agenda as $agendaTask)

          <tr>
            <!-- ID -->
            <td>
              {{ $agendaTask->id }}
            </td>

            <!-- Description -->
            <td>
              {{ $agendaTask->description }}
            </td>
          </tr>

          @endforeach

        </tbody>
      </table>

      <a class="large expanded button" href="/tasks">Go to tasks</a>
    </div>

    <div class="column large-4">
      <h3>Scoreboard</h3>
      <!-- TODO: CSS later -->
      <p><span style="font-size: 30px">{{ $completeTasks }}</span> 
      @if ($completeTasks == 1)
        task 
      @else
        tasks
      @endif completed</p>

      <p><span style="font-size: 30px">{{ $incompleteTasks }}</span> 
      @if ($incompleteTasks == 1)
        task 
      @else
        tasks
      @endif left to do</p>

      <p><span style="font-size: 30px">{{ $overdueTasks }}</span> overdue
      @if ($overdueTasks == 1)
        task 
      @else
        tasks
      @endif</p>
    </div>
  </div>

  @endif

@endsection
