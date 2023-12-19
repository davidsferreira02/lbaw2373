@extends('layouts.app')

@section('content')

<style>
#tasksContainer {
  display: flex;
  flex-wrap: wrap;
}

.task-card {
  width: calc(33.33% - 20px);
  margin: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.button-container {
    display: flex;
    justify-content: left;
    margin-bottom: 20px;
}

.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 1px;
    max-width: 800%;
    margin-left: auto;
    margin-right: auto;
}

.search-form {
    display: flex;
}

#searchTaskInput {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 5px;
}

#searchTaskIcon {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f0f0f0;
}
</style>

<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>

<h2> Tasks from {{$project->title}} <h2>
    <div class="button-container">
        <form method="GET" action="{{ route('project.show', ['title' => $project->title]) }}">
            <button type="submit" class="btn btn-primary">See Project details</button>
        </form>
    
        @if($project->members->contains(Auth::user()))
            <form method="GET" action="{{ route('task.create', ['title' => $project->title]) }}">
                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        @endif

        <select id="priorityFilter">
            <option value="all">All Priorities</option>
            <option value="Low">Low Priority</option>
            <option value="Medium">Medium Priority</option>
            <option value="High">High Priority</option>
        </select>
    </div>

    <div class="search-container">
        <form action="{{ route('task.search', ['title' => $project->title]) }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search tasks..." id="searchTaskInput">
            <button type="submit" id="searchTaskIcon"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div id="tasksContainer">
        @forelse ($tasks as $task)
            <div class="task-card" data-priority="{{ $task->priority }}">
                <!-- Detalhes da tarefa -->
                <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                    <h3><strong>Title:</strong> {{ $task->title }}</h3>
                </a>
                <p><strong>Priority:</strong>{{ $task->priority }}</p>
                <p><strong>Deadline:</strong>{{ $task->deadline }}</p>
                <p><strong>IsCompleted: </strong>
                    @if($task->iscompleted)
                        true
                    @else
                        false
                    @endif
                </p>
                @foreach ($task->owners as $owner)
                <p><strong>Owner:</strong> {{ $owner->name }}</p>
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
    
                
            @endforeach

                
        @empty
            <!-- Se não houver tarefas -->
            <p>No tasks found for this project.</p>
        @endforelse
    </div>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('.complete-form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(form);
                    var url = form.getAttribute('action');
                    var taskCard = form.closest('.task-card');
        
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        var button = form.querySelector('button');
                        var paragraphs = taskCard.querySelectorAll('p');
        
                        paragraphs.forEach(function(paragraph) {
                            if (paragraph.textContent.includes('isCompleted:')) {
                                if (data.iscompleted) {
                                    button.textContent = 'Uncomplete';
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-warning');
                                    paragraph.innerHTML = '<strong>isCompleted:</strong> <span>true</span>';
                                } else {
                                    button.textContent = 'Mark as completed';
                                    button.classList.remove('btn-warning');
                                    button.classList.add('btn-success');
                                    paragraph.innerHTML = '<strong>isCompleted:</strong> <span>false</span>';
                                }
                            }
                        });
                    })
                    .catch(function(error) {
                        console.error('Erro ao completar a tarefa:', error);
                    });
                });
            });
        });
        </script>
        
    @endsection
