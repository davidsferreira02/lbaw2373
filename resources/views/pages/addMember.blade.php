@extends('layouts.app')

@section('content')
    <h1>Adicionar Membro ao Projeto: {{ $project->title }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('project.Memberstore', ['title' => $project->title]) }}">
        @csrf

        <div class="form-group">
            <label for="username">Username do membro</label>
                <input type="text" id="username" name="username" class="form-control">
        </div>

        <button type="submit"href="{{ route('project.index') }}" class="btn btn-primary">Add Member</button>
    </form>
@endsection
