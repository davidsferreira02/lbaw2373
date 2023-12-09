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

    <input type="text" id="searchInput" placeholder="Search by title">
  

    
    <div id="tasksContainer">
        @forelse ($tasks as $task)
            <div class="task-card" data-priority="{{ $task->priority }}">
                <!-- Detalhes da tarefa -->
                <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                    <h3><strong>title:</strong> {{ $task->title }}</h3>
                </a>

                <p><strong>content:</strong>{{ $task->content }}</p>
                <p><strong>priority:</strong>{{ $task->priority }}</p>
                <p><strong>deadline:</strong>{{ $task->deadline }}</p>
                <p><strong>dateCreation:</strong>{{ $task->datecreation }}</p>
                <p><strong>isCompleted: </strong>
                    @if($task->iscompleted)
                        true
                    @else
                        false
                    @endif
                </p>
                
                    
                  
                  
                
                

    
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
