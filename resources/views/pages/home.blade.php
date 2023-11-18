
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
    
</body>
</html>
@endsection