@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a New Project</h1>
        <form method="post" action="{{ route('project.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Project Name</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Project Description</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Project</button>
        </form>
    </div>
@endsection
