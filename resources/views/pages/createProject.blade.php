@extends('layouts.app')

@section('content')
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i>
</a>

<div class="container square-container">
    <h1>Create a New Project</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="post" action="{{ route('project.store') }}">
        @csrf

        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" id="title" name="title" class="form-control">
            <span class="error">
                {{ $errors->first('title') }}
            </span>
        </div>

        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea id="description" name="description" class="form-control"></textarea>
            <span class="error">
                {{ $errors->first('description') }}
            </span>
        </div>

        <div class="form-group">
            <label for="theme">Project Theme</label>
            <textarea id="theme" name="theme" class="form-control"></textarea>
            <span class="error">
                {{ $errors->first('theme') }}
            </span>
        </div>

        <button type="submit" href="{{ route('project.index') }}" class="btn btn-primary">Create Project</button>
    </form>
</div>
@endsection
