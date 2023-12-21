
@extends('layouts.app')

@section('content')
<!-- Exibição dos erros -->


<a href="{{ route('project.show', ['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>
<h1>Add Member to {{$project->title}}</h1>

<!-- Formulário de pesquisa -->
<form method="get" action="{{ route('search.username',['title' => $project->title]) }}">
    @csrf
    <div class="form-group">
        <label for="username">username</label>
        <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}">
    </div>
    <button type="submit" class="btn btn-primary">Search User</button>
    @if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
</form>

<!-- Exibição dos resultados da busca -->
@if(isset($users))
    @if(count($users) > 0)
        <h2>Search Results:</h2>
        @foreach ($users as $user)
            <!-- Exemplo simples de exibição dos usuários encontrados -->
            <p>{{ $user->username }}</p>
             <form method="post" action="{{ route('project.Memberstore', ['title'=>$project->title,'username' => $user->username]) }}">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to invite this user to the project?')">Add</button>

              
            </form>
        @endforeach
    @else
        <p>No users found with this username{{ $username }}.</p>
    @endif
@endif
@endsection