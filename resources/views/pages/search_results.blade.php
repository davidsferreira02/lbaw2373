@extends('layouts.app')

@section('content')
  

    @if($search)
        <h1>Resultados da pesquisa: {{ $search }}</h1>
    @endif

    <h2>Usuários:</h2>
    @if(count($users) > 0)
        <ul>
            @foreach($users as $user)
                <li>{{ $user->name }}</li>
               
            @endforeach
        </ul>
    @else
        <p>Nenhum usuário encontrado.</p>
    @endif

    <h2>Projetos:</h2>
    @if(count($projects) > 0)
        <ul>
            @foreach($projects as $project)
            <button class="btn btn-primary" onclick="location.href='{{ route('project.show', ['title' => $project->title]) }}'">
                {{ $project->title }}
            </button>
               
            @endforeach
        </ul>
    @else
        <p>Nenhum projeto encontrado.</p>
    @endif

    <a href="{{ route('project.home') }}" class="btn btn-primary">Voltar para a Página Inicial</a>
@endsection
