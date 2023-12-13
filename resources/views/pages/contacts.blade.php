<!-- resources/views/about_us.blade.php -->

@extends('layouts.app')

@section('content')


@if(Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isblocked)
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(Auth::check() && Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(!Auth::check())
<a href="{{ route('login') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(Auth::check() && Auth::user()->isblocked )
<a href="{{ route('blocked') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif
    <div class="container">
        <h1>Contacts</h1>
        <p>Welcome to our website! We are a fantastic team working together to bring you amazing content.</p>
        <!-- Add any other content or details about your team/company here -->
    </div>
@endsection
