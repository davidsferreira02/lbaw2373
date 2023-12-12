@extends('layouts.app')

@section('content')
    <h1>Search Results for "{{ $search }}"</h1>

    @if($task->isEmpty())
        <p>No tasks found.</p>
    @else
        <ul>
            @foreach($task as $task)
                <li>
                    <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                        {{ $task->title }}
                    </a>
                    <!-- Adicione mais detalhes da tarefa, se necessário -->
                </li>
            @endforeach
        </ul>
    @endif
@endsection
