@extends('layouts.app')

@section('content')

<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> 
</a>

<h1>Search Results for "{{ $search }}"</h1>

<div class="search-container">
    <form action="{{ route('task.search', ['title' => $project->title]) }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search tasks..." id="searchTaskInput">
        <button type="submit" id="searchTaskIcon"><i class="fas fa-search"></i></button>
    </form>
</div>

<div id="tasksContainer">
    @forelse ($task as $task)
        <div class="task-card" data-priority="{{ $task->priority }}">
            <!-- Detalhes da tarefa -->
            <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                <h3><strong>title: </strong> {{ $task->title }}</h3>
            </a>
            <p><strong>priority: </strong>{{ $task->priority }}</p>
            <p><strong>deadline: </strong>{{ $task->deadline }}</p>
            <p><strong>isCompleted: </strong>
                @if($task->iscompleted)
                    true
                @else
                    false
                @endif
            </p>
            @foreach ($task->owners as $owner)
            <p><strong>Owner: </strong> {{ $owner->name }}</p>
        
            
        @endforeach

            @if(!Auth::user()->isAdmin())

            <form method="POST" action="{{ route('task.complete', ['title' => $project->title, 'taskId' => $task->id]) }}" class="complete-form">
                @csrf
                @method('PATCH') 

                <!-- Verificação e renderização do botão -->
                @if($task->iscompleted)
                    <input type="hidden" name="iscompleted" value="false">
                    <button type="submit" class="btn btn-warning">
                        Uncomplete
                    </button>
                @else
                    <input type="hidden" name="iscompleted" value="true">
                    <button type="submit" class="btn btn-success">
                        Mark as completed
                    </button>
                @endif
            </form>
            @endif
        </div>
    @empty
        <!-- Se não houver tarefas -->
        <p>No tasks found for this project.</p>
    @endforelse
</div>

@endsection
