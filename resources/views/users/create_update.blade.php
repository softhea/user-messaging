
@extends('layouts.bootstrap')

@section('content')

<h1>{{ $title }}</h1>

<form method="POST" action="{{ $route }}">
    @csrf
    @if (Route::is('users.edit'))
        {{ method_field("PUT") }}
    @endif
	<input type="text" name="username" value="{{ old('username') ?? (isset($user) ? $user->username : '') }}" placeholder="Username"><br>
	<br>
	<input type="text" name="email" value="{{ old('email') ?? (isset($user) ? $user->email : '') }}" placeholder="Email Address"><br>
	<br>
	<select name="role_id">
		@foreach ($roles as $id => $roleName)		
            <option value="{{ $id }}" 
                @if ($id == $defaultRoleId) 
                    selected 
                @endif
            >{{ $roleName }}</option>
		@endforeach
	</select><br>
	<br>
	<input type="password" name="password" value="" placeholder="Password"><br>
	<br>
	<input type="submit" name="save" value="Save">
</form>

@if ($errors->any())
    <div class="alert alert-danger mt-5">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection