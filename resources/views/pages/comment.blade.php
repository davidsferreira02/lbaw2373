@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Detalhes da Tarefa</title>
    <!-- Inclua a biblioteca jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2><strong>title:</strong> {{ $task->title }}</h2>
    <!-- Outros detalhes da tarefa... -->

    <h2>Comentários:</h2>
    <!-- Seção de comentários -->
    @foreach($comments as $comment)
        <div class="comment">
            <!-- Detalhes do comentário -->
            <button type="button" onclick="handleLike('{{ $comment->likedByCurrentUser() ? 'dislike' : 'like' }}', {{ $comment->id }}, '{{ $project->title }}', '{{ $task->id }}', '{{ $task->title }}', this)">
                @if($comment->likedByCurrentUser())
                    <i class="fa-solid fa-thumbs-up"></i>
                @else
                    <i class="far fa-thumbs-up"></i>
                @endif
            </button>
                
            <!-- Contagem de Likes -->
            <p id="likesCount_{{ $comment->id }}">Total de Likes: {{ $comment->likes()->count() }}</p>
        </div>
    @endforeach

    <!-- Formulário para adicionar comentários -->
    <form method="POST" action="{{ route('comments.store', ['title' => $project->title, 'taskId' => $task->id]) }}">
        @csrf
        <textarea name="content" placeholder="Escreva seu comentário"></textarea>
        <button type="submit">Enviar Comentário</button>
    </form>
    
    <div id="commentsContainer"></div>

    <!-- Script JavaScript para lidar com as requisições de like/dislike -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function handleLike(action, commentId, projectId, taskId, titleTask, button) {
            $.ajax({
                method: 'PATCH',
                url: `/project/${projectId}/task/${titleTask}/comment/${commentId}/like/store`,
                data: {
                    _token: '{{ csrf_token() }}',
                    comment_id: commentId,
                    user_id: '{{ Auth::id() }}',
                    action: action
                },
                success: function(response) {
                    let likesCount = response.likesCount;
                    if (!isNaN(likesCount)) {
                        $(`#likesCount_${commentId}`).text(`Total de Likes: ${likesCount}`);
                        
                        // Alterar dinamicamente o ícone do botão
                        if (action === 'like') {
                            $(button).html('<i class="fa-solid fa-thumbs-up"></i>');
                        } else if (action === 'dislike') {
                            $(button).html('<i class="far fa-thumbs-up"></i>');
                        }
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>
@endsection
