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


    <div class="profile">
         <h1>
            @if($user->id === Auth::user()->id)
                My Profile: {{ $user->name }}
            @else
                Profile: {{ $user->name }}
            @endif
        </h1>
        <p>Email: {{ $user->email }}</p>
        <p>Username: {{ $user->username }}</p>

        <h2>Profile Project:</h2>
        <ul>
            @foreach($project as $project)
                <li>
                    <a href="{{ route('project.show', ['title' => $project->title]) }}">
                        {{ $project->title }}
                    </a>
                </li>
            @endforeach
        </ul>

        @if($project->count() === 0)
        <p>Nenhum projeto encontrado para este usuário.</p>
    @endif

        @if($user->id === Auth::user()->id)
            <a href="{{ route('profile.edit', ['id' => $user->id]) }}" class="btn btn-primary">
                Edit Profile
            </a>
        @endif
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


        @if(Auth::check() && Auth::user()->id === $user->id)
        <form action="{{ route('profile.delete', ['id' => $user->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Excluir Perfil</button>
        </form>
    @endif
    

    </div>

    <img src="{{ $user->getProfileImage() }}">
          


    
@endsection
