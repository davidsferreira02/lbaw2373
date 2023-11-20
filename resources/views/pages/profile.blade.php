@extends('layouts.app')

@section('content')
    <div class="container">
        @isset($profile)
        <h1>{{ $profile->username }}</h1>
        <p><strong>Name:</strong> {{ $profile->name }}</p>
        <p><strong>Email:</strong> {{ $profile->email }}</p>
        <p><strong>Birthdate:</strong> {{ count($profile->birthdate) }}</p>
        <p><strong>Profile Pic:</strong> {{ count($profile->profile_pic) }}</p>
       
    @endisset
    
     
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
