@extends('layouts.app')

@section('content')

<a href="{{ route('task.show',['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> 
</a>

<h1>Search Results for "{{ $search }}"</h1>

<div class="task-squares">
    @if($task->isEmpty())
        <p>No tasks found.</p>
    @else
        @foreach($task as $task)
            <div class="task-square">
                <a href="{{ route('task.comment', ['taskId' => $task->id,'title'=>$project->title]) }}">
                    <h2> {{ $task->title }} </h2>
                </a>
                <!-- Add more task details if needed -->
            </div>
        @endforeach
    @endif
</div>

@endsection
