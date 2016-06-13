@extends('layouts.base')

@section('pageTitle', 'Update User')

@section('content')

{{ Form::model(['route' => 'users.update', $user->id]) }}

{{ Form::label('email', 'New Email: ') }}
{{ Form::email('email') }}
{{ $errors->first('email') }}

{{ Form::label('password', 'New Password: ') }}
{{ Form::password('password') }}
{{ $errors->first('password') }}

{{ Form::submit('Register') }}

{{ Form::close() }}

@endsection
