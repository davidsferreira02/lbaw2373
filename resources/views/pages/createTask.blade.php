@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Criar Nova Tarefa</h2>

        <form action="{{ route('task.store', ['title' => $project->title]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Conteúdo:</label>
                <textarea class="form-control" id="content" name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="priority">Prioridade:</label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="high">Alta</option>
                    <option value="medium">Média</option>
                    <option value="low">Baixa</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deadline">Prazo:</label>
                <input type="date" class="form-control" id="deadline" name="deadline" required>
            </div>

            <button type="submit"href="{{ route('project.index') }}" class="btn btn-primary">Create Tarefa</button>
        </form>
    </div>
@endsection


