@extends('layouts.app')

@section('content')
    <h1>Add Member to Project: {{ $project->title }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('project.Memberstore', ['title' => $project->title]) }}">
        @csrf

        <div class="form-group">
            <label for="username">Member username</label>
                <input type="text" id="username" name="username" class="form-control">
        </div>

        <button type="submit"href="{{ route('project.index') }}" class="btn btn-primary">Add Member</button>
    </form>
@endsection
