@extends('layouts.app')

@section('content')

<style>
  .actions-bottom-right {
    position: fixed;
    right: 20px;
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    gap: 10px; /* Adds spacing between buttons */
  }
  .leave-favorite-buttons {
    position: fixed;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
  }
</style>

@if($project->members->contains(Auth::user())||Auth::user()->isAdmin())
<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
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
        <div class="actions-bottom-right">
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
       

        <button id="editProject">Edit Project </button>

        </div>
        <dialog>

      
    <div class="project">
        <h1>Edit Project</h1>

        <a id="closeEditProject">
            <i class="fa-solid fa-xmark"></i>
        </a>

        <form id="editProjectSubmit" method="POST" action="{{ route('project.update', ['title' => $project->title]) }}">
            @csrf
            @method('PUT')

            <label for="title">Project Title:</label>
            <input type="text" id="title" name="title" value="{{ $project->title }}" required>
            <span class="error">
                {{ $errors->first('title') }}
              </span>

            <label for="description">Project Description:</label>
            <input type="text" id="description" name="description" value="{{ $project->description }}" required>

            <span class="error">
                {{ $errors->first('description') }}
              </span>

            <label for="theme">Project Theme:</label>
            <input type="text" id="theme" name="theme" value="{{ $project->theme }}" required>

            <span class="error">
                {{ $errors->first('theme') }}
              </span>

            
   

            <button type="submit">Save</button>
        </form>
    </div>


        </dialog>
        
        @endif
      
  
        @if($project->members->contains(Auth::user()))
        <div class="leave-favorite-buttons">
          <form action="{{ route('project.leave', ['title' => $project->title]) }}" method="POST" class="my-3 leave-btn">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja sair do projeto?')">Leave Project</button>
          </form>

          <div class="edit-favorite-buttons">
              @if(!$isFavorite)
                  <a href="{{ route('project.favorite', ['title' => $project->title]) }}" class="btn btn-primary favorite-btn" data-project-id="{{ $project->id }}" data-is-favorite="false">
                      <i class="fa-regular fa-star"></i>
                  </a>
              @endif

              @if($isFavorite)
                  <a href="{{ route('project.noFavorite', ['title' => $project->title]) }}" class="btn btn-primary favorite-btn" data-project-id="{{ $project->id }}" data-is-favorite="true">
                      <i class="fa-solid fa-star"></i>
                  </a>
              @endif
          </div>
        </div>
    @endif
  




<script>

    const editProjectButton = document.querySelector("#editProject");
    const modal = document.querySelector("dialog");
    const buttonClose = document.querySelector("#closeEditProject");
    const editProjectSubmit = document.querySelector(".editProjectSubmit");
    
    
    
    
    editProjectButton.onclick = function() {
      modal.showModal();
    };
    
    buttonClose.onclick = function() {
      modal.close();
    };
    
    editProjectSubmit.addEventListener("submit", async function(event) {
      event.preventDefault();
      const formData = new FormData(editProjectSubmit);
    
      try {
        const response = await fetch(editProjectSubmit.action, {
          method: 'PUT',
          body: formData
        });
    
        if (!response.ok) {
          throw new Error('Resposta inesperada do servidor');
        }
    
        // Fechar o diálogo se a atualização for bem-sucedida
        modal.close();
      } catch (error) {
        const errorMessages = document.querySelectorAll('.error');
      
    
        modal.showModal(); // Mantém o modal aberto após o erro
      }
    });
    
            </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const favoriteBtns = document.querySelectorAll('.favorite-btn');

  favoriteBtns.forEach(btn => {
    btn.addEventListener('click', async function(event) {
      event.preventDefault();

      const projectId = this.getAttribute('data-project-id');
      const isFavorite = this.getAttribute('data-is-favorite') === 'true';

      this.disabled = true;

      try {
        if (isFavorite) {
          // Se já for um favorito, remove-o
          const removeFavoriteResponse = await fetchFavoriteAction(this.getAttribute('href'), projectId, true);

          if (!removeFavoriteResponse.ok) {
            console.log(removeFavoriteResponse);
            throw new Error('Erro ao remover o favorito');
          }

          this.setAttribute('data-is-favorite', 'false');
        } else {
          // Remove todos os favoritos anteriores no mesmo projeto
          await removePreviousFavorites(projectId);

          // Adiciona o novo favorito
          const addNewFavoriteResponse = await fetchFavoriteAction(this.getAttribute('href'), projectId, true);

          if (!addNewFavoriteResponse.ok) {
            console.log(addNewFavoriteResponse);
            throw new Error('Erro ao atualizar o estado do favorito');
          }

          this.setAttribute('data-is-favorite', 'true');
        }

        toggleIconClass(this);
      } catch (error) {
        console.error('Erro:', error);
        this.setAttribute('data-is-favorite', isFavorite ? 'true' : 'false');
        toggleIconClass(this);
      } finally {
        this.disabled = false;
      }
    });
  });
});

async function removePreviousFavorites(projectId) {
  const favoriteBtns = document.querySelectorAll(`.favorite-btn[data-project-id="${projectId}"][data-is-favorite="true"]`);

  for (const btn of favoriteBtns) {
    try {
      const removeFavoriteResponse = await fetchFavoriteAction(btn.getAttribute('href'), projectId, true);
      if (!removeFavoriteResponse.ok) {
        console.log(removeFavoriteResponse);
        throw new Error('Erro ao remover favorito anterior');
      }
      btn.setAttribute('data-is-favorite', 'false');
      toggleIconClass(btn);
    } catch (error) {
      console.error('Erro ao remover favorito:', error);
      throw error;
    }
  }
}
function fetchFavoriteAction(url, projectId, removePreviousFavorite) {
  const requestBody = {
    projectId,
    removePreviousFavorite
  };

  return fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestBody),
  });
}

function toggleIconClass(button) {
  const icon = button.querySelector('i');
  icon.classList.toggle('fa-regular');
  icon.classList.toggle('fa-solid');
}



</script>
    @endsection