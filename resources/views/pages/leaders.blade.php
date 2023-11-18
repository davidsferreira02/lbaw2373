

@extends('layouts.app') 

@section('content')
    <h1>Leaders from {{ $project->title }}</h1>
    <ul>
        @foreach ($leaders as $leader)
            <li>{{ $leader->name }}</li> 
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>

@endsection


