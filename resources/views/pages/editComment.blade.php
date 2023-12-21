@extends('layouts.app')

@section('content')
    <a href="{{ route('task.comment', ['title'=>$project->title,'taskId'=>$task->id]) }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> 
    </a>

    <div class="profile">
        <h1>Edit Comment</h1>

        <form method="POST" action="{{ route('comment.update', ['title' => $project->title,'titleTask'=>$task->title,'idComment'=>$comment->id]) }}" >
            @csrf
            @method('PUT')

            <label for="content">Content:</label>
            <input type="text" id="content" name="content" value="{{ $comment->content }}" required>
            <span class="error">
                {{ $errors->first('content') }}
            </span>


            <button type="submit">Save</button>
        </form>
    </div>
@endsection
