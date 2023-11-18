@extends('layouts.app')

@section('content')
    <h2>Tarefas do Projeto: {{ $project->title }}</h2>

    <button class="btn btn-primary filter-btn" data-priority="Low">Low Priority</button>
    <button class="btn btn-primary filter-btn" data-priority="Medium">Medium Priority</button>
    <button class="btn btn-primary filter-btn" data-priority="High">High Priority</button>

    <div id="tasksContainer">
        @if($tasks)
            @forelse ($tasks as $task)
                <div class="task-card" data-priority="{{ $task->priority }}">
                    <h3><strong>title:</strong>{{ $task->title }}</h3>
                    <p><strong>content:</strong>{{ $task->content }}</p>
                    <p><strong>priority:</strong>{{ $task->priority }}</p>
                    <p><strong>deadline:</strong>{{ $task->deadline }}</p>
                    <p><strong>dateCreation:</strong>{{ $task->dateCreation }}</p>
                    <p><strong>isCompleted:</strong>{{ $task->isCompleted }}</p>
                    @foreach ($task->assigned as $user)
                        <p><strong>Assigned:</strong>{{ $user->name }}</p>
                    @endforeach
                    @foreach ($task->owners as $owner)
                        <p><strong>Owner:</strong>{{ $owner->name }}</p>
                    @endforeach
                </div>
            @empty
                <p>Nenhuma tarefa encontrada para este projeto.</p>
            @endforelse
        @else
            <p>Nenhuma tarefa encontrada para este projeto.</p>
        @endif
    </div>
@endsection
