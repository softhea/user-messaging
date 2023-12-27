
@extends('layouts.bootstrap')

@section('content')

<h1>Not Allowed!</h1>

@if (Session::has('error'))
	<div class="alert alert-danger" role="alert">
		{{ Session::get('error') }}
	</div>
@endif

@endsection