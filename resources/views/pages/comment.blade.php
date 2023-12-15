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
        </div>
        @if($owner->id === Auth::id())
        <form method="POST" action="{{ route('comment.delete', ['title'=>$project->title,'titleTask'=>$task->title,'idComment' => $comment->id]) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>

    

    @endif



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



@endsection
