
@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Home</h1>


    <a href="{{ route('project.index') }}" class="btn btn-primary">My Projects</a>
    <a href="{{ route('project.create') }}" class="btn btn-success">Create Project</a>
    <a href="{{ route('pending.invites') }}" class="btn btn-success"> Pendent Invites</a>
    
    <form method="POST" action="/send">
        @csrf
        <label for="name">Your name</label>
        <input id="name" type="text" name="name" placeholder="Name" required>
        <label for="email">Your email</label>
        <input id="email" type="email" name="email" placeholder="Email" required>
        <button type="submit">Send</button>
    </form>


</body>
</html>
@endsection