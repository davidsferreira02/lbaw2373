@extends('layouts.app')

@section('content')

<form action="{{ route('task.store', ['title' => $project->title]) }}" method="POST">
    @csrf

    <div>
        <label for="title">Título da Tarefa:</label>
        <input type="text" id="title" name="title">
    </div>

    <div>
        <label for="content">Descrição da Tarefa:</label>
        <textarea id="content" name="content"></textarea>
    </div>

    <div>
        <label for="priority">Prioridade:</label>
        <select name="priority" id="priority">
            <option value="Low">Baixa</option>
            <option value="Medium">Média</option>
            <option value="High">Alta</option>
        </select>
    </div>

    <div>
        <label for="deadline">Prazo:</label>
        <input type="date" id="deadline" name="deadline">
    </div>

    <div>
        <label for="assigned">Atribuir a:</label>
        <select name="assigned" id="assigned">
            @foreach($project->members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
           
        </select>
    </div>


    <button type="submit">Criar Tarefa</button>
</form>
@endsection