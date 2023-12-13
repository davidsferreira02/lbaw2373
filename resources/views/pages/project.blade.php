@extends('layouts.app')

@section('content')
@if(Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
@endif

@if(!Auth::user()->isAdmin())
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
@endif
</a>
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
        @if($project->archived)
        <a href="{{ route('project.archived', ['title' => $project->title]) }}" class="btn btn-primary"> <i class="fa-solid fa-bookmark"></i></a>
        @endif
        @if(!$project->archived)
        <a href="{{ route('project.archived', ['title' => $project->title]) }}" class="btn btn-primary"> <i class="fa-regular fa-bookmark"></i></a>
        @endif
        @endif

        @if($project->leaders->contains(Auth::user()) || Auth::user()->isAdmin() )
        <a href="{{ route('project.editProject', ['title' => $project->title]) }}" class="btn btn-primary">Edit Project</a>
        
        @endif
        @if($project->members->contains(Auth::user()))
        <a href="{{ route('task.create', ['title' => $project->title]) }}" class="btn btn-primary">Create Task</a>
        @endif
        @if($project->members->contains(Auth::user()) || Auth::user()->isAdmin() )

        <a href="{{ route('task.show', ['title' => $project->title]) }}" class="btn btn-primary">See Task</a>
        @endif
        @if($project->members->contains(Auth::user()))
        
           <form action="{{ route('project.leave', ['title' => $project->title]) }}" method="POST" class="my-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja sair do projeto?')">Leave Project</button>
    </form>
        @if(!$isFavorite)
        
<a href="{{ route('project.favorite', ['title' => $project->title]) }}" class="btn btn-primary"><i class="fa-regular fa-star"></i></a>
@endif
@if($isFavorite)

<a href="{{ route('project.noFavorite', ['title' => $project->title]) }}" class="btn btn-primary"><i class="fa-solid fa-star"></i></a>
        @endif

      

        @endif
    </div>


    

   

@endsection
