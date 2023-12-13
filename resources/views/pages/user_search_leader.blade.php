@extends('layouts.app')

@section('content')
    <!-- Mensagem de erro se o campo estiver vazio -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('project.show', ['title'=>$project->title]) }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Formulário de pesquisa -->
    <form method="get" action="{{ route('search.usernameLeader',['title' => $project->title]) }}">
        @csrf
        <div class="form-group">
            <label for="username">Member username</label>
            <input type="text" id="username" name="username" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Search Member</button>
    </form>

    <!-- Exibição dos resultados da busca -->
    @if(isset($users))
        @if(count($users) > 0)
            <h2>Search Results:</h2>
            @foreach ($users as $user)
                <!-- Exemplo simples de exibição dos usuários encontrados -->
                <p>{{ $user->username }}</p>
                 <form method="post" action="{{ route('project.Leaderstore', ['title'=>$project->title,'username' => $user->username]) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
            @endforeach
        @else
            <p>No users found with this  username{{ $username }}.</p>
        @endif
    @endif
@endsection
