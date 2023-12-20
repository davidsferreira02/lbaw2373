

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
          <a id="archivedButton" href="{{ route('project.archived', ['title' => $project->title]) }}" class="btn btn-primary">
              <i class="fa-solid fa-bookmark"></i>
          </a>
      @else
          <a id="archivedButton" href="{{ route('project.archived', ['title' => $project->title]) }}" class="btn btn-primary">
              <i class="fa-regular fa-bookmark"></i>
          </a>
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
        <form action="{{ route('project.leave', ['title' => $project->title]) }}" method="POST" class="my-3 leave-btn">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja sair do projeto?')">Leave Project</button>
        </form>

        @if($isFavorite)
        <div class="edit-favorite-buttons">
          <button class="btn btn-primary favorite-btn" id="favoriteButton">
              <i class="fa-solid fa-star"></i>
          </button>
      </div>
      @endif
      @if(!$isFavorite)
      <div class="edit-favorite-buttons">
        <button class="btn btn-primary favorite-btn" id="favoriteButton">
            <i class="fa-regular fa-star"></i>
        </button>
    </div>
    @endif
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
 const favoriteButton = document.getElementById('favoriteButton');
let isFavorite = @json($isFavorite);


function toggleFavorite() {
    if (isFavorite) {
        favoriteButton.innerHTML = '<i class="fa-solid fa-star"></i>';
    } else {
        favoriteButton.innerHTML = '<i class="fa-regular fa-star"></i>';
    }
}

function updateFavoriteIcon() {
    toggleFavorite();

    fetch(`{{ route('project.favorite', ['title' => $project->title]) }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ isFavorite: isFavorite })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao atualizar o favorito');
        }
        // Lidar com a resposta do servidor, se necessário
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

favoriteButton.addEventListener('click', function () {
    isFavorite = !isFavorite;
    updateFavoriteIcon();
});

toggleFavorite(); // Movido para o final para exibir corretamente o ícone no carregamento inicial

</script>



<!-- Seção de scripts no final da página -->


<script>
  const archivedButton = document.getElementById('archivedButton');
  let isArchived = {{ $project->archived ? 'true' : 'false' }};
  console.log(isArchived);
  console.log(archivedButton);

  // Atualização do ícone com base no estado inicial de archived
  if (isArchived) {
    archivedButton.querySelector('i').className = 'fa-solid fa-bookmark'; // Ícone sólido quando archived é verdadeiro
  } else {
    archivedButton.querySelector('i').className = 'fa-regular fa-bookmark'; // Ícone regular quando archived é falso
  }

  archivedButton.addEventListener('click', function(event) {
    event.preventDefault(); // Prevenir o comportamento padrão do link

    fetch("{{ route('project.archived', ['title' => $project->title]) }}", {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ isArchived: !isArchived })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error updating archived status');
      }
      return response.json();
    })
    .then(data => {
      console.log('Response from server:', data);
      isArchived = data.isArchived;

      // Atualização do ícone com base no novo estado de archived após a resposta
      if (isArchived) {
        archivedButton.querySelector('i').className = 'fa-solid fa-bookmark'; // Ícone sólido quando archived é verdadeiro
      } else {
        archivedButton.querySelector('i').className = 'fa-regular fa-bookmark'; // Ícone regular quando archived é falso
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
</script>


    @endsection