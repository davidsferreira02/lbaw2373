@extends('layouts.app')

@section('content')
    <div class="profile">
        <h1>Edit Project</h1>

        <form method="POST" action="{{ route('project.update', ['title' => $project->title]) }}">
            @csrf
            @method('PUT')

            <label for="title">Project Title:</label>
            <input type="text" id="title" name="title" value="{{ $project->title }}" required>

            <label for="title">Project Description:</label>
            <input type="text" id="description" name="description" value="{{ $project->description }}" required>

            <label for="theme">Project Theme:</label>
            <input type="text" id="theme" name="theme" value="{{ $project->theme }}" required>

            
   

            <button type="submit">Save</button>
        </form>
    </div>
@endsection
