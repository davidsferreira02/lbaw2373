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
    top: 0;
    right: 0;
    width: 100px;
    height: auto;
}

.buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: auto;
    gap: 10px;
}

.buttons button {
    position: absolute;
    bottom: 0;
    right: 0;
    margin-bottom: 10px;
}

.project-card {
    width: calc(33.33% - 20px);
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
    </h1>
    <p>Email: {{ $user->email }}</p>
    <p>Username: {{ $user->username }}</p>

    <h2>Profile Projects:</h2>
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

    @if($project->count() === 0)
        <p>Nenhum projeto encontrado para este usuário.</p>
    @endif

    <div class="buttons">
        @if(Auth::check() && Auth::user()->isAdmin() && Auth::user()->id !== $user->id && !$user->isAdmin())
            @if($user->isblocked)
                <form action="{{ route('admin.block', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Desbloquear Usuário</button>
                </form>
            @else
                <form action="{{ route('admin.block', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Bloquear Usuário</button>
                </form>
            @endif
        @endif

        @if($user->id === Auth::user()->id)
            <a href="{{ route('profile.edit', ['id' => $user->id]) }}" class="btn btn-primary">
                Edit Profile
            </a>
        @endif

        @if(Auth::check() && Auth::user()->id === $user->id || Auth::user()->isAdmin())
            <form action="{{ route('profile.delete', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Excluir Perfil</button>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        @endif
    </div>

    </div>      


    
@endsection
