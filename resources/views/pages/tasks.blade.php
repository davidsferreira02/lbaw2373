@extends('layouts.app')

@section('content')

<a href="{{ route('project.show', ['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>
    <select id="priorityFilter">
        <option value="all">All Priorities</option>
        <option value="Low">Low Priority</option>
        <option value="Medium">Medium Priority</option>
        <option value="High">High Priority</option>
    </select>

    <form action="{{ route('task.search', ['title' => $project->title]) }}" method="GET">
        <input type="text" name="search" placeholder="Search tasks...">
        <button type="submit">Search</button>
    </form>

<div id="tasksContainer">
        @forelse ($tasks as $task)
            <div class="task-card" data-priority="{{ $task->priority }}">
                <!-- Detalhes da tarefa -->
                <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                    <h3><strong>title:</strong> {{ $task->title }}</h3>
                </a>
                <p><strong>priority:</strong>{{ $task->priority }}</p>
                <p><strong>deadline:</strong>{{ $task->deadline }}</p>
                <p><strong>isCompleted: </strong>
                    @if($task->iscompleted)
                        true
                    @else
                        false
                    @endif
                </p>
                @foreach ($task->owners as $owner)
                <p><strong>Owner:</strong> {{ $owner->name }}</p>
                @if ($owner->id === Auth::id()) 
                <button id="editTask">Edit Task </button>
                <dialog>
                <div class="profile">
                    <h1>Edit Task</h1>
            
                    <a id="closeEditTask">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    <form id="editTaskSubmit" method="POST" action="{{ route('task.update', ['title' => $task->project->title, 'taskTitle' => $task->title, 'task' => $task->id]) }}">
                        @csrf
                        @method('PUT')
            
                        
                        <div>
                        <label for="title">Task Title:</label>
                        <input type="text" id="title" name="title" value="{{ $task->title }}" required>
                        <span class="error">
                            {{ $errors->first('title') }}
                          </span>
                        </div>
            
                        <div>
                        <label for="content">Task Content:</label>
                        <input type="text" id="content" name="content" value="{{ $task->content }}" required>
                        <span class="error">
                            {{ $errors->first('content') }}
                          </span>
                    </div>
                        <div>
                        <label for="priority">Priority:</label>
                        <select name="priority" id="priority">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <span class="error">
                            {{ $errors->first('priority') }}
                          </span>
                    </div>
                        <div>
                        <label for="deadline">DeadLine:</label>
                        <input type="date" id="deadline" name="deadline" value="{{ $task->deadline }}" required>
                        <span class="error">
                            {{ $errors->first('deadline') }}
                          </span>
                    </div>
                        <div>
                        <label for="assigned">Assigned to:</label>
                        <select name="assigned" id="assigned">
                            @foreach($project->members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        <span class="error">
                            {{ $errors->first('assigned') }}
                          </span>
                    </div>
                        
                    
                    
                     
                    
                        <button type="submit">Save</button>
                    </form>
                </div>

            </dialog>




                <form id = "deleteTask" action="{{ route('task.delete', ['taskTitle' => $task->title, 'title' => $project->title]) }}" method="POST" class="my-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja apagar a task?')">Delete Task</button>
                </form>

               
                
            @endif
                
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

