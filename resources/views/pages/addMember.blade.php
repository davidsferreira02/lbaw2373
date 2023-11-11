@extends('layouts.app')

@section('content')
    <h1>Adicionar Membro ao Projeto: {{ $project->title }}</h1>

    <form method="post" action="{{ route('project.storeMember', ['title' => $project->title]) }}">
        @csrf

        <div class="form-group">
            <label for="email">E-mail do Membro</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Adicionar Membro</button>
    </form>
@endsection
