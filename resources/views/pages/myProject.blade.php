@extends('layouts.app')

@section('content')
    <h1>Project List</h1>
    <ul>
        @foreach($projects as $project)
            <li>
                <a href="{{ route('project.show', ['title' => $project->title]) }}">
                    {{ $project->title }}
                </a>
                
            </li>
        @endforeach
    </ul>

    <!-- Botão para voltar para a página inicial (home) -->
    <a href="{{ route('project.home') }}" class="btn btn-primary">Go back</a>
@endsection
