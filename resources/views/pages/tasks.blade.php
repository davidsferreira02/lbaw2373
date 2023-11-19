@extends('layouts.app')

@section('content')
    <h2>Tarefas do Projeto: {{ $project->title }}</h2>
    <input type="text" id="searchInput" placeholder="Pesquisar por título...">

    <select id="priorityFilter">
        <option value="all">All Priorities</option>
        <option value="Low">Low Priority</option>
        <option value="Medium">Medium Priority</option>
        <option value="High">High Priority</option>
    </select>
    
    <select id="completedFilter">
        <option value="all">All Tasks</option>
        
    </select>
    
    



    <div id="tasksContainer">
        @if($tasks)
            @forelse ($tasks as $task)
                <div class="task-card" data-priority="{{ $task->priority }}">
                    <h3><strong>title:</strong>{{ $task->title }}</h3>
                    <p><strong>content:</strong>{{ $task->content }}</p>
                    <p><strong>priority:</strong>{{ $task->priority }}</p>
                    <p><strong>deadline:</strong>{{ $task->deadline }}</p>
                    <p><strong>dateCreation:</strong>{{ $task->datecreation }}</p>
                    <p><strong>isCompleted:</strong>
                        @if($task->iscompleted)
                            true
                        @else
                            false
                        @endif
                    </p>
                
                    
                    @foreach ($task->assigned as $user)
                        <p><strong>Assigned:</strong>{{ $user->name }}</p>
                    @endforeach
                    @foreach ($task->owners as $owner)
                        <p><strong>Owner:</strong>{{ $owner->name }}</p>
                    @endforeach
                    @unless($task->iscompleted)
                    <form method="POST" action="{{ route('task.complete', ['title' => $project->title, 'taskId' => $task->id]) }}">
                        @csrf
                        @method('PATCH') 
                        
                        <button type="submit" class="btn btn-success">
                            Mark as completed
                        </button>
                    </form>
                @endunless
                    </form>
                    
            

                </div>
            @empty
                <p>No tasks found for this project.</p>
            @endforelse
        @else
            <p>No tasks found for this project</p>
        @endif
    </div>
    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>
@endsection
