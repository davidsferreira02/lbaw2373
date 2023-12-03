@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes da Tarefa</title>
</head>
<body>
    <h2><strong>title:</strong> {{ $task->title }}</h2>
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
    <hr>

    <h2>Comentários:</h2>
    @foreach($comments as $comment)
        <div class="comment">
            <p>{{ $comment->content }}</p>
            <p> {{$comment->date}}</p>
        </div>
    @endforeach
    <!-- HTML da Página -->
    <form method="POST" action="{{ route('comments.store', ['title' => $project->title, 'taskId' => $task->id]) }}">
        @csrf
        <textarea name="content" placeholder="Escreva seu comentário"></textarea>
        <button type="submit">Enviar Comentário</button>
    </form>
    
    <div id="commentsContainer">
        <!-- Aqui serão exibidos os comentários -->
    </div>

    @endsection