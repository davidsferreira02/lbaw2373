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
        <div class="owner">
            <h2>Owner 1</h2>
            <p>Name: David dos Santos Ferreira</p>
            <p>Email: up202006302@fe.up.pt</p>
        </div>
       
        <!-- Owner 2 -->
        <div class="owner">
            <h2>Owner 2</h2>
            <p>Name:  Ana Sofia Oliveira Teixeira</p>
            <p>Email: up201806629@fe.up.pt</p>
        </div>
    </div>
    </div>
@endsection
