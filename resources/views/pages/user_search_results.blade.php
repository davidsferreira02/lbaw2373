@extends('layouts.app')

@section('content')
 

<a href="{{ route('project.show', ['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>

<h1>Add Member to {{$project->title}}</h1>

    <!-- Formulário de pesquisa -->
    <form method="get" action="{{ route('search.username',['title' => $project->title]) }}">
        @csrf
        <div class="form-group">
            <label for="username">username</label>
            <input type="text" id="username" name="username" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Search User</button>
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    </form>

    <!-- Exibição dos resultados da busca -->
    @if(isset($users))
    @if(count($users) > 0)
        <h2>Search Results:</h2>
        @foreach ($users as $user)
            <!-- Exibição do usuário e do botão -->
            <div class="user-container">
                <p>{{ $user->username }}</p>
                <form method="post" action="{{ route('project.Memberstore', ['title'=>$project->title,'username' => $user->username]) }}" class="custom-form">
                    @csrf
                    <button type="submit" class="btn btn-success custom-button" onclick="return confirm('Are you sure you want to invite this user to be a member of this project?')">Add</button>
                </form>
            </div>
        @endforeach
    @else
        <p>No users found with this username {{ $username }}</p>
    @endif
@endif

@endsection