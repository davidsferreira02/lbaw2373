

@extends('layouts.app') 

@section('content')
    <h1>Members from {{ $project->title }}</h1>
    <ul>
        @foreach ($members as $member)
           
            <button class="btn btn-primary" onclick="location.href='{{ route('profile', ['id' => $member->id,'project' => $member->projectMember]) }}'">
                {{ $member->name }}
            </button>
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>

@endsection


