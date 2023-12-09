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


    

    <!-- Modal de erro -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">
                    <p id="errorModalMessage"></p>
                </div>
                <div class="modal-footer">
               
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JavaScript (inclua esta biblioteca) -->
   
    
    <script>
        // Exibe o modal de erro e define a mensagem
        $('#errorModal').modal('show');
        $('#errorModalMessage').text('Aqui vai a mensagem de erro específica que você deseja exibir.');
    </script>


   
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            // Exibe o modal de erro
            $('#errorModalMessage').text('{{ session('error') }}');
            $('#errorModal').modal('show');
        @endif
    });
</script>

@endsection

