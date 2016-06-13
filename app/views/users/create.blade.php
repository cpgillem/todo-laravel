@extends('layouts.base')

@section('pageTitle', 'Register')

@section('content')

{{ Form::open(['route' => 'users.store']) }}

<div class="row column">
  {{ Form::label('email', 'Email: ') }}
  {{ Form::email('email') }}
  {{ $errors->first('email') }}
</div>

<div class="row column">
  {{ Form::label('password', 'Password: ') }}
  {{ Form::password('password') }}
  {{ $errors->first('password') }}
</div>

<div class="row column">
  {{ Form::submit('Register', ['class' => 'button']) }}
</div>

{{ Form::close() }}

@endsection
