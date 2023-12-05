@extends('layouts.app')

@section('content')
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
        @forelse ($tasks as $task)
            <div class="task-card" data-priority="{{ $task->priority }}">
                <!-- Detalhes da tarefa -->
                <h3><strong>title:</strong>{{ $task->title }}</h3>
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
    
    


    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>

   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.complete-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var taskCard = form.closest('.task-card');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            success: function(response) {
                var button = form.find('button');
                if (response.iscompleted) {
                    button.text('Uncomplete').removeClass('btn-success').addClass('btn-warning');
                } else {
                    button.text('Mark as completed').removeClass('btn-warning').addClass('btn-success');
                }

                var isCompletedElement = taskCard.find('p strong:contains("isCompleted:")');
                if (isCompletedElement.length > 0) {
                    isCompletedElement.text('isCompleted: ' + response.iscompleted);
                } else {
                    taskCard.append('<p><strong>isCompleted:</strong> ' + response.iscompleted + '</p>');
                }
            },
            error: function(error) {
                console.error('Erro ao completar a tarefa:', error);
            }
        });
    });
});


</script>
    

@endsection
