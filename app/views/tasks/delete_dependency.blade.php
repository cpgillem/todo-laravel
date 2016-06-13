@extends('layouts.base')

@section('pageTitle', 'Delete Dependency')

@section('content')

{{ Form::open(['action' => ['TasksController@destroyDependency', $task->id]]) }}

@include('includes.dependency_fields')

<div class="row column">
  {{ Form::submit('Delete') }}
  {{ link_to_route('tasks.index', 'Back', [], ['class' => 'button']) }}
</div>

{{ Form::close() }}

@endsection
