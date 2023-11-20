@extends('layouts.app')

@section('content')
  

    @if($search)
        <h1>Search results: {{ $search }}</h1>
    @endif

    <h2>Users:</h2>
    @if(count($users) > 0)
        <ul>
            @foreach($users as $user)

                <button class="btn btn-primary" onclick="location.href='{{ route('profile', ['id' => $user->id,'project' => $user->member()]) }}'">
                    {{ $user->name }}
                </button>
            @endforeach
        </ul>
    @else
        <p>No users found.</p>
    @endif

    <h2>Projects:</h2>
    @if(count($projects) > 0)
        <ul>
            @foreach($projects as $project)
            <button class="btn btn-primary" onclick="location.href='{{ route('project.show', ['title' => $project->title]) }}'">
                {{ $project->title }}
            </button>
               
            @endforeach
        </ul>
    @else
        <p>No projects found.</p>
    @endif

    <a href="{{ route('project.home') }}" class="btn btn-primary">Go Home</a>
@endsection
