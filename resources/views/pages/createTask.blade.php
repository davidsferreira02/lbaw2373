@extends('layouts.app')

@section('content')


<a href="{{ route('task.show', ['title'=>$project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>

<h1>Create a New Task in {{$project->title}}</h1>
<form action="{{ route('task.store', ['title' => $project->title]) }}" method="POST">
    @csrf

    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title">
        <span class="error">
            {{ $errors->first('title') }}
          </span>
    </div>

    <div>
        <label for="content">Description:</label>
        <textarea id="content" name="content"></textarea>
        <span class="error">
            {{ $errors->first('content') }}
          </span>
    </div>

    <div>
        <label for="priority">Priority</label>
        <select name="priority" id="priority">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
    </div>

    <div>
        <label for="deadline">DeadLine:</label>
        <input type="date" id="deadline" name="deadline">
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
    </div>


    <button type="submit">Task Create</button>
</form>

@endsection