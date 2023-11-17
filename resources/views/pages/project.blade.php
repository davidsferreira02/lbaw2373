@extends('layouts.app')

@section('content')
    <div class="container">
        @isset($project)
        <h1>{{ $project->title }}</h1>
        <p><strong>Description:</strong> {{ $project->description }}</p>
        <p><strong>Theme:</strong> {{ $project->theme }}</p>
        <p><strong>Members:</strong> {{ count($project->members) }}</p>
        <p><strong>Leaders:</strong> {{ count($project->leaders) }}</p>
       
    @endisset
    
        <!-- Adicione mais informações conforme necessário -->
        <a href="{{ route('project.addMember', ['title' => $project->title]) }}" class="btn btn-primary">Add Member</a>
        <a href="{{ route('project.addLeader', ['title' => $project->title]) }}" class="btn btn-primary">Add Leader</a>

        
    </div>
@endsection
