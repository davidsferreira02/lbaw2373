

@extends('layouts.app')

@section('content')


@if(Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isblocked)
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i>
</a>
@endif

@if(Auth::check() && Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(!Auth::check())
<a href="{{ route('login') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(Auth::check() && Auth::user()->isblocked )
<a href="{{ route('blocked') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif
    <div class="container">
        <h1>Main Features</h1>
        <ul>
            <li>Create Projects</li>
            <li>Create Tasks</li>
            <li>Assign Tasks</li>
            <li>Add Members</li>
            <li>Comment on Tasks</li>
            
        </ul>
    </div>
@endsection
