
@extends('layouts.bootstrap')

@section('content')

<h1>Send Message to {{ $user->getUsername() }}</h1>

<form method="POST" action="{{ route('messages.store') }}">
    @csrf
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Message</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="8" name="message"></textarea>
    </div>
    <input type="hidden" name="receiver_id" value="{{ $user->getId() }}">
    <button type="submit" class="btn btn-primary mt-10">Send</button>
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