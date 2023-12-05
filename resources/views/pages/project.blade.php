@extends('layouts.app')

@section('content')
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
    
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
        <a href="{{ route('project.editProject', ['title' => $project->title]) }}" class="btn btn-primary">Edit Project</a>
      
        @endif
        @if($project->members->contains(Auth::user()))
        <a href="{{ route('task.create', ['title' => $project->title]) }}" class="btn btn-primary">Create Task</a>
        <a href="{{ route('task.show', ['title' => $project->title]) }}" class="btn btn-primary">See Task</a>
        @if(!$isFavorite)
        
<a href="{{ route('project.favorite', ['title' => $project->title]) }}" class="btn btn-primary"><i class="fa-regular fa-star"></i></a>
@endif
@if($isFavorite)

<a href="{{ route('project.noFavorite', ['title' => $project->title]) }}" class="btn btn-primary"><i class="fa-solid fa-star"></i></a>
        @endif

        @if(!$project->archived)
        <button id="archiveButton" data-title="{{ $project->title }}" class="btn btn-primary ">
            <i class="fa-regular fa-bookmark"></i>
        </button>
    @endif
    
    @if($project->archived)
        <button id="unarchiveButton" data-title="{{ $project->title }}" class="btn btn-primary ">
            <i class="fa-solid fa-bookmark"></i> 
        </button>
    @endif


        @endif
    </div>
   
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#archiveButton').click(function() {
            let title = $(this).data('title');
            toggleArchived(title);
        });

        $('#unarchiveButton').click(function() {
            let title = $(this).data('title');
            toggleArchived(title);
        });

        function toggleArchived(title) {
            $.ajax({
                url: '/project/' + title + '/archived', 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    
                    window.location.reload(); 
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
</script>