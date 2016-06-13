@extends('layouts.base')

@section('pageTitle', 'Add Dependency')

@section('content')

  {{ Form::open(['action' => ['TasksController@storeDependency', $task->id]]) }}

  @include('includes.dependency_fields')

  <div class="row column">
    {{ Form::submit('Add', ['class' => 'button']) }}
    {{ link_to_route('tasks.index', 'Back', [], ['class' => 'button']) }}
  </div>

  {{ Form::close() }}

@endsection
