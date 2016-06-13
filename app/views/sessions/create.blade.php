@extends('layouts.base')

@section('pageTitle', 'Login')

@section('content')

{{ Form::open(['route' => 'sessions.store']) }}

<div class="row column">
  {{ Form::label('email', 'Email') }}
  {{ Form::email('email') }}
  <p class="help-text">{{ $errors->first('email') }}</p>

  {{ Form::label('password', 'Password') }}
  {{ Form::password('password') }}
  <p class="help-text">{{ $errors->first('password') }}</p>
</div>

<div class="row column">
  {{ Form::submit('Login', ['class' => 'button']) }}
</div>

{{ Form::close() }}

@endsection
