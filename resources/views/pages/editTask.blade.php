@extends('layouts.app')

@section('content')
    <div class="profile">
        <h1>Edit Task</h1>

       
        <form method="POST" action="{{ route('task.update', ['title' => $task->project->title, 'taskTitle' => $task->title, 'task' => $task->id]) }}">
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
@endsection
