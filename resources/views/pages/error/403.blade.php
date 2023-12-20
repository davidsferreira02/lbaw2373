@extends('layouts.app')

@section('page_title', 'Forbidden')

@section('content')
    <h2 class="mt-4 mb-4">403 - Forbidden Access</h2>

    <div>
        <p>Sorry, but you are not allowed to enter here.</p>

       

        <a href={{ url('/home') }} class="mt-4 btn btn-outline-secondary">Go back to home</a>
    </div>
@endsection
