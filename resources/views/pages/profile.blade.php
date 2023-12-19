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
</style>


@if(Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
@endif

@if(!Auth::user()->isAdmin())
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
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
        @foreach($project as $project)
            <li>
                <a href="{{ route('task.show', ['title' => $project->title]) }}">
                    {{ $project->title }}
                </a>
            </li>
        @endforeach
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
