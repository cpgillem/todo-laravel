@extends('layouts.base')

@section('pageTitle', 'Create Task')

@section('content')

{{ Form::open(['route' => 'tasks.store']) }}

@include('includes.task_fields')

<div class="row column">
  {{ Form::submit('Create', ['class' => 'button']) }}
</div>

{{ Form::close() }}

@endsection
