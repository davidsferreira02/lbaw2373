

@extends('layouts.app') 

@section('content')
    <h1>Leaders from {{ $project->title }}</h1>
    <ul>
        @foreach ($leaders as $leader)
         
            <button class="btn btn-primary" onclick="location.href='{{ route('profile', ['id' => $leader->id,'project' => $leader->member()]) }}'">
                {{ $leader->name }}
            </button>
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>

@endsection


