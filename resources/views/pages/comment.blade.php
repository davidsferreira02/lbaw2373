@extends('layouts.app')

@section('content')


<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> 
</a>

<head>
    <title>Task Details</title>
</head>
<body>
    <h2><strong>title:</strong> {{ $task->title }}</h2>
    <p><strong>content:</strong>{{ $task->content }}</p>
    <p><strong>priority:</strong>{{ $task->priority }}</p>
    <p><strong>deadline:</strong>{{ $task->deadline }}</p>
    <p><strong>dateCreation:</strong>{{ $task->datecreation }}</p>
    <p><strong>isCompleted:</strong>{{ $task->iscompleted == 1 ? 'True' : 'False' }}</p>
    @foreach ($task->owners as $owner)
    <p><strong>Owner:</strong> {{ $owner->username }}</p>
@endforeach
     @foreach($task->assigned as $assigned)

  <p><strong>Assigned:</strong> {{ $assigned->username }}</p>
  @endforeach
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


  

    <h2>Comments:</h2>
    <!-- Seção de comentários -->

 

    @foreach($comments as $comment)
        <div class="comment">
            @foreach($comment->owner as $user)
           <h3>{{ $user->username }}</h3>
            
@endforeach
<p><strong>Date:</strong> {{ $comment->date }}</p>
            <p><strong>Comment:</strong> {{ $comment->content }}</p>
            <!-- Detalhes do comentário -->
            @if($comment->likedByCurrentUser())
                <button type="button" onclick="handleLike('dislike', {{ $comment->id }}, '{{ $project->title }}', '{{ $task->id }}', '{{ $task->title }}', this)">
                    <i class="fa-solid fa-thumbs-up"></i>
                </button>
            @else
                <button type="button" onclick="handleLike('like', {{ $comment->id }}, '{{ $project->title }}', '{{ $task->id }}', '{{ $task->title }}', this)">
                    <i class="far fa-thumbs-up"></i>
                </button>
            @endif
                
            <!-- Contagem de Likes -->
            <p id="likesCount_{{ $comment->id }}">Total of Likes: {{ $comment->likes()->count() }}</p>
            @if($owner->id === Auth::id())
            <form id="deleteComment" method="POST" action="{{ route('comment.delete', ['title'=>$project->title,'titleTask'=>$task->title,'idComment' => $comment->id]) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
    
        
    
        @endif
    
        </div>
      


    @endforeach

    @if(!Auth::user()->isAdmin())
    <!-- Formulário para adicionar comentários -->
    <form method="POST" action="{{ route('comments.store', ['title' => $project->title, 'taskId' => $task->id]) }}">
        @csrf
        <textarea name="content" placeholder="Escreva seu comentário"></textarea>
        <button type="submit">Send Comment</button>
    </form>
    
    <div id="commentsContainer"></div>
@endif
    
    <script>
        function handleLike(action, commentId, projectId, taskId, titleTask, button) {
            fetch(`/project/${projectId}/task/${titleTask}/comment/${commentId}/like/store`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    comment_id: commentId,
                    user_id: '{{ Auth::id() }}',
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                let likesCount = data.likesCount;
                if (!isNaN(likesCount)) {
                    document.getElementById(`likesCount_${commentId}`).innerText = `Total de Likes: ${likesCount}`;
                    
                    // Alterar dinamicamente o ícone do botão
                    if (action === 'like') {
                        button.innerHTML = '<i class="fa-solid fa-thumbs-up"></i>';
                        button.setAttribute('onclick', `handleLike('dislike', ${commentId}, '${projectId}', '${taskId}', '${titleTask}', this)`);
                    } else if (action === 'dislike') {
                        button.innerHTML = '<i class="far fa-thumbs-up"></i>';
                        button.setAttribute('onclick', `handleLike('like', ${commentId}, '${projectId}', '${taskId}', '${titleTask}', this)`);
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    </script>


</body>


<script>

    const editTaskButton = document.querySelector("#editTask");
    const modal = document.querySelector("dialog");
    const buttonClose = document.querySelector("#closeEditTask");
    const editTaskSubmit = document.querySelector(".editTaskSubmit");
    
    
    
    
    editTaskButton.onclick = function() {
      modal.showModal();
    };
    
    buttonClose.onclick = function() {
      modal.close();
    };
    
    editTaskSubmit.addEventListener("submit", async function(event) {
      event.preventDefault();
      const formData = new FormData(editTaskSubmit);
    
      try {
        const response = await fetch(editTaskSubmit.action, {
          method: 'PUT',
          body: formData
        });
    
        if (!response.ok) {
          throw new Error('Resposta inesperada do servidor');
        }
    
        // Fechar o diálogo se a atualização for bem-sucedida
        modal.close();
      } catch (error) {
        const errorMessages = document.querySelectorAll('.error');
      
    
        modal.showModal(); // Mantém o modal aberto após o erro
      }
    });
    
            </script>
    
    

{{-- <script>
function like(id) {
    const button = document.querySelector("#post" + id + " button");
    if (button.className === "not-clicked") {
        button.className = "clicked";
        button.innerHTML = "Liked";
        sendAjaxRequest('post', '../post/like', {id: id});
    }
}

const pusher = new Pusher(pusherAppKey, {
    cluster: pusherCluster,
    encrypted: true
});

const channel = pusher.subscribe('tutorial02');
channel.bind('notification-postlike', function(data) {

    const notification = document.getElementById('notification');
    const closeButton = document.getElementById('closeButton');
    const notificationText = document.getElementById('notificationText');
    notificationText.textContent = data.message;
    notification.classList.add('show');

    closeButton.addEventListener('click', function() {
        notification.classList.remove('show');
    });

    setTimeout(function() {
        notification.classList.remove('show');
    }, 5000);
});
</script>

--}}
@endsection


