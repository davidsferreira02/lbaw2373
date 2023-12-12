@extends('layouts.app')

@section('content')

<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> 
</a>
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
