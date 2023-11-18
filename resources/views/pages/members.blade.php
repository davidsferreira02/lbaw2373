

@extends('layouts.app') 

@section('content')
    <h1>Membros from {{ $project->title }}</h1>
    <ul>
        @foreach ($members as $member)
            <li>{{ $member->name }}</li> 
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>

@endsection


