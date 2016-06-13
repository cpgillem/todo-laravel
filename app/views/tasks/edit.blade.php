@extends('layouts.base')

@section('pageTitle', 'Edit Task')

@section('content')

  {{ Form::model($task, ['route' => ['tasks.update', $task->id], 'method' => 'PUT']) }}

  @include('includes.task_fields')

  <div class="row column">
    {{ Form::submit('Update', ['class' => 'button']) }}
  </div>

  {{ Form::close() }}
</div>

@endsection
