@extends('layouts.app')

@section('content')
    <h1>Adicionar Leader ao Projeto: {{ $project->title }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('project.Leaderstore', ['title' => $project->title]) }}">
        @csrf

        <div class="form-group">
            <label for="username">Username do possivel Leader</label>
                <input type="text" id="username" name="username" class="form-control">
        </div>

        <button type="submit"href="{{ route('project.index') }}" class="btn btn-primary">Adicionar Leader</button>
    </form>
@endsection
