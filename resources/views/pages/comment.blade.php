@extends('layouts.app')

@section('content')

<style>
    .task-cont {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Grid layout for task details */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 10px;
        margin-top: 20px;
    }

    /* Styles for individual columns */
    .detail-column {
        border-right: 1px solid #ccc;
        padding-right: 15px;
    }

    .detail-column:last-child {
        border-right: none;
        padding-right: 0;
    }

    /* Additional styles for buttons inside the task container */
    .task-cont button {
        margin-top: 10px;
        margin-right: 10px;
    }

    .comment {
    display: flex;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    position: relative;
}

.user-details {
    display: flex;
    align-items: center;
}

.username {
    margin: 0;
}

.comment-details {
    flex: 1;
    padding-left: 10px;
}

.comment-date {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
}

.like-section {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.likes-count {
    margin-left: 10px;
}

.comment-actions {
    position: absolute;
    bottom: 0;
    right: 0;
    display: flex;
    margin-top: 10px;
}

.comment-actions button {
    margin-right: 5px;
}

</style>

<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> 
</a>

<head>
    <title>Task Details</title>
</head>
<body>
<div class="task-cont">
    <h2><strong>Title:</strong> {{ $task->title }}</h2>
        <div class="details-grid">
            <div class="detail-column">
                <p><strong>Content:</strong> {{ $task->content }}</p>
                <p><strong>Priority:</strong> {{ $task->priority }}</p>
            </div>
            <div class="detail-column">
              <p><strong>Date Creation:</strong> {{ $task->datecreation }}</p>    
              <p><strong>Deadline:</strong> {{ $task->deadline }}</p> 
            </div>
            <div class="detail-column">
                <p><strong>Is Completed:</strong> {{ $task->iscompleted == 1 ? 'True' : 'False' }}</p>
                <p><strong>Owner:</strong>
                    @foreach ($task->owners as $owner)
                        {{ $owner->username }}
                    @endforeach
                </p>
                <p><strong>Assigned:</strong>
                    @foreach($task->assigned as $assigned)
                        {{ $assigned->username }}
                    @endforeach
                </p>
            </div>
        </div>

        @if ($owner->id === Auth::id() || Auth::user()->projectLeader->contains($project)) 
    <button id="editTask">Edit Task</button> 
    <dialog>
        <h1>Edit Task</h1>

        <div class="profile">
            <a id="closeEditTask">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <form id="editTaskSubmit" method="POST" action="{{ route('task.update', ['title' => $task->project->title, 'taskTitle' => $task->title, 'task' => $task->id]) }}">
                @csrf
                @method('PUT')
                <div class="center-content">
            
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
        
            <button type="submitTask">Save</button>
        </form>
    </div>

</dialog>
               <form id = "deleteTask" action="{{ route('task.delete', ['taskTitle' => $task->title, 'title' => $project->title]) }}" method="POST" class="my-3">
                    @csrf
                    @method('DELETE')
                    <button type="submitTask" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja apagar a task?')">Delete Task</button>
                </form>
           
            
@endif
</div>


  

    <h2>Comments:</h2>
    <!-- Seção de comentários -->

 

    @foreach($comments as $comment)
        <div class="comment">
            @foreach($comment->owner as $user)
                <div class="user-details">
                    <img src="{{ $user->getProfileImage() }}" class="profile-image">
                    <p class="username">{{ $user->username }}</p>
                </div>
            @endforeach
            <div class="comment-details">
            <p><strong>Date:</strong> {{ $comment->date }}</p>
            <p><strong>Comment:</strong> {{ $comment->content }}</p>
            <!-- Likes -->
            <div class="like-section">
                @if($comment->likedByCurrentUser() && !Auth::user()->isAdmin())
                    <button type="button" onclick="handleLike('dislike', {{ $comment->id }}, '{{ $project->title }}', '{{ $task->id }}', '{{ $task->title }}', this)">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </button>
                @elseif(!$comment->likedByCurrentUser() && !Auth::user()->isAdmin())
                    <button type="button" onclick="handleLike('like', {{ $comment->id }}, '{{ $project->title }}', '{{ $task->id }}', '{{ $task->title }}', this)">
                        <i class="far fa-thumbs-up"></i>
                    </button>
                @endif
                <p class="likes-count" id="likesCount_{{ $comment->id }}">Total of Likes: {{ $comment->likes()->count() }}</p>
                @if($user->id === Auth::id()||Auth::user()->projectLeader->contains($project))
                <div class="comment-actions">
                    <form id="deleteComment" method="POST" action="{{ route('comment.delete', ['title'=>$project->title,'titleTask'=>$task->title,'idComment' => $comment->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submitComment"><i class="fa-solid fa-trash"></i></button>
                    </form>

                    <form method="GET" action="{{ route('comment.edit', ['title'=>$project->title,'titleTask'=>$task->title,'idComment' => $comment->id]) }}">
                        <button type="submit" class="btn btn-primary">Edit Comment</button>
                    </form>
                </div>
                @endif
            </div> 
        </div>
    
            
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
    
    editTaskSubmit.addEventListener("submitTask", async function(event) {
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

    editTaskButton.onclick = function() {
      modal.showModal();
    };
    
    buttonClose.onclick = function() {
      modal.close();
    };
    
    editTaskSubmit.addEventListener("submitComment", async function(event) {
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
    
    <script>

        const editButton = document.querySelector("#editComment");
        
        const modal = document.querySelector("dialog");
        const buttonClose = document.querySelector("#closeEditComment");
        const editCommentSubmit = document.querySelector(".editCommentSubmit");
       
        
        
        
        editCommentButton.onclick = function() {
          modal.showModal();
        };
        
        buttonClose.onclick = function() {
          modal.close();
        };
        
        editCommentSubmit.addEventListener("submitComment", async function(event) {
          event.preventDefault();
          const formData = new FormData(editCommentSubmit);
        
          try {
            const response = await fetch(editCommentSubmit.action, {
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
    
        editCommentButton.onclick = function() {
          modal.showModal();
        };
        
        buttonClose.onclick = function() {
          modal.close();
        };
        
        editCommentSubmit.addEventListener("submitComment", async function(event) {
          event.preventDefault();
          const formData = new FormData(editCommentSubmit);
        
          try {
            const response = await fetch(editCommentSubmit.action, {
              method: 'PUT',
              body: formData
            });
        
            if (!response.ok) {
              throw new Error('Resposta inesperada do servidor');
            }

            modal.close();
          } catch (error) {
            const errorMessages = document.querySelectorAll('.error');
          
        
            modal.showModal(); // Mantém o modal aberto após o erro
          }
        });
        
                </script>
    

 
        
    


@endsection


