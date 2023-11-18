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
        @if($project->leaders->contains(Auth::user()))
   
        <a href="{{ route('project.addMember', ['title' => $project->title]) }}" class="btn btn-primary">Add Member</a>
        <a href="{{ route('project.addLeader', ['title' => $project->title]) }}" class="btn btn-primary">Add Leader</a>
      
        @endif
        <a href="{{ route('task.create', ['title' => $project->title]) }}" class="btn btn-primary">Create Task</a>
        <a href="{{ route('task.show', ['title' => $project->title]) }}" class="btn btn-primary">See Task</a>
    </div>
@endsection
