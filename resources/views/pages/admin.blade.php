@extends('layouts.app')

@section('content')
    <h2>Admin Dashboard</h2>

    <!-- Conteúdo específico da área de administração -->
    <p>Welcome to the administration area!</p>

    <h3>Users:</h3>
    <ul>
        @foreach($users as $user)
            <li>
                <a href="{{ route('profile', ['id' => $user->id]) }}">
                    {{ $user->username }} - {{ $user->email }}</li>
                </a>
                
          
            <!-- Adicione mais informações do usuário conforme necessário -->
        @endforeach
    </ul>

    <h3>Projects:</h3>
    <ul>
        @foreach($projects as $project)
        <li>
            <a href="{{ route('task.show', ['title' => $project->title]) }}">
                {{ $project->title }} - {{ $project->description }}
            </a>
        </li>
            <!-- Adicione mais informações do projeto conforme necessário -->
        @endforeach
    </ul>
@endsection
