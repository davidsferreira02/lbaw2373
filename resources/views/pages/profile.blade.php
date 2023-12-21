@extends('layouts.app')
@section('content')
<style>
.profile {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ccc;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.profile img {
    position: absolute;
    top: 50px;
    right: 50px;
    width: 100px;
    height: 100px;
}



.project-card {
    width: calc(45.33% - 20px);
    margin: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

#projectsContainer {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Adjust spacing between project cards */
    
}
</style>


@if(Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(!Auth::user()->isAdmin())
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

<div class="profile">
    <img src="{{ $user->getProfileImage()}}" alt="Profile Image">
        <h1>
        @if($user->id === Auth::user()->id)
            My Profile: {{ $user->name }}
        @else
            Profile: {{ $user->name }}
        @endif
        @if($user->isAdmin())
        <i class="fa-solid fa-user-tie"></i>
        @endif
    </h1>
    <p>Email: {{ $user->email }}</p>
    <p>Username: {{ $user->username }}</p>

    @if(!$user->isAdmin())
    @if($user->id===Auth::user()->id || Auth::user()->isAdmin())
    <h2>Profile Projects:</h2>
    @elseif($user->id!==Auth::user()->id && !$user->isAdmin() && !Auth::user()->isAdmin())
    <h2>Common Projects:</h2>
    @endif

    
    <ul>
   
        @if($project->count() === 0)
            <p>No projects found for this user.</p>
        @else
            <div id="projectsContainer">
                @foreach($project as $project)
                    <div class="project-card">
                        <a href="{{ route('task.show', ['title' => $project->title]) }}">
                            <h3>{{ $project->title }}</h3>
                        </a>
                        <p><strong>Theme:</strong>{{ $project->theme }}</p>
                        <p><strong>Description:</strong>{{ $project->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif

    </ul>
@endif


    <div class="buttons">
        @if(Auth::check() && Auth::user()->isAdmin() && Auth::user()->id !== $user->id && !$user->isAdmin())
        <div class="admin-block-button">
            @if($user->isblocked)
                <form action="{{ route('admin.block', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Unblock User</button>
                </form>
            @else
                <form action="{{ route('admin.block', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Block Usuário</button>
                </form>
            @endif
        @endif
        </div>

        @if($user->id === Auth::user()->id)
        <button id="editProfile"  class="btn btn-primary">
            Edit Profile
        </button>
        
<dialog>
    <h1>Edit Profile</h1>
    <div class="profile">
        <a id="closeEditProfile">
            <i class="fa-solid fa-xmark"></i>
        </a>
        <form id="editProfileSubmit"method="POST" action="{{ route('profile.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" required>
            <span class="error">
                {{ $errors->first('name') }}
            </span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
            <span class="error">
                {{ $errors->first('email') }}
            </span>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}" required>
            <span class="error">
                {{ $errors->first('username') }}
            </span>

            <img src="{{ $user->getProfileImage() }}" alt="profile_image">

            <label for="image">Change Photo:</label>
            <input name="image" type="file" id = "image" class="from-control-file">
            @if ($errors->has('profile_image'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('profile_image') }}</strong>
                </span>
            @endif

            <button type="submit">Save</button>
        </form>
    </div>
</dialog>
        @endif

        @if(Auth::check() && Auth::user()->id === $user->id || Auth::user()->isAdmin())
            <form action="{{ route('profile.delete', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this account?')">Delete Profile</button>

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
        @endif
    </div>

    </div>      


    
    <script>

        const editProfileButton = document.querySelector("#editProfile");
        const modal = document.querySelector("dialog");
        const buttonClose = document.querySelector("#closeEditProfile");
        const editProfileSubmit = document.querySelector(".editProfileSubmit");
        
        
        
        
        editProfileButton.onclick = function() {
          modal.showModal();
        };
        
        buttonClose.onclick = function() {
          modal.close();
        };
        
        editProfileSubmit.addEventListener("submit", async function(event) {
          event.preventDefault();
          const formData = new FormData(editProfileSubmit);
        
          try {
            const response = await fetch(editProfiletSubmit.action, {
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


@endsection
