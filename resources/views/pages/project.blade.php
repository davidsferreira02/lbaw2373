@extends('layouts.app')

@section('content')
    <div class="container">
        @isset($project)
        <div class="project-header">
            <h1>{{ $project->title }}</h1>
            @if($project->members->contains(Auth::user()))
              
            @endif
        </div>
        <p><strong>Description:</strong> {{ $project->description }}</p>
        <p><strong>Theme:</strong> {{ $project->theme }}</p>
        <a href="{{ route('project.showMember', ['title' => $project->title]) }}" class="btn btn-primary"><strong> Members</strong> {{ count($project->members) }}</a>
        <a href="{{ route('project.showLeader', ['title' => $project->title]) }}" class="btn btn-primary"><strong> Leaders</strong> {{ count($project->leaders) }}</a>
    @endisset
    
        <!-- Adicione mais informações conforme necessário -->
        @if($project->leaders->contains(Auth::user()))
   
        <a href="{{ route('project.addMember', ['title' => $project->title]) }}" class="btn btn-primary">Add Member</a>
        <a href="{{ route('project.addLeader', ['title' => $project->title]) }}" class="btn btn-primary">Add Leader</a>
        <a href="{{ route('project.editProject', ['title' => $project->title]) }}" class="btn btn-primary">Edit Project</a>
      
        @endif
        @if($project->members->contains(Auth::user()))
        <a href="{{ route('task.create', ['title' => $project->title]) }}" class="btn btn-primary">Create Task</a>
        <a href="{{ route('task.show', ['title' => $project->title]) }}" class="btn btn-primary">See Task</a>
        @if(!$isFavorite)
        <a href="{{ route('project.favorite', ['title' => $project->title]) }}" class="btn btn-primary">Favorite</a>
@endif
@if($isFavorite)
@endif
<i class="fa-solid fa-star"></i>
        @endif
    </div>
    <a href="{{ route('project.home') }}" class="btn btn-primary">Go back</a>
@endsection
