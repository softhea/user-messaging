
@extends('layouts.bootstrap')

@section('content')

<h1>Invatam Sa Programam</h1>

@if (Session::has('error'))
	<div class="alert alert-danger" role="alert">
		{{ Session::get('error') }}
	</div>
@endif

@if (Session::has('message'))
	<div class="alert alert-success" role="alert">
		{{ Session::get('message') }}
	</div>
@endif

<h3>PHP & MySQL</h3>

<p>HTML</p>

<p>JavaScript</p>

<p>CSS</p>

@endsection