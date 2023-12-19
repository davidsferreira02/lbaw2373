
@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <title>Home </title>
</head>
<body>
    <h1>Home <i class="fa-solid fa-house"></i></h1>


    <div style="display: flex; gap: 10px;">
        <button class="btn btn-primary" onclick="window.location.href='{{ route('project.index') }}'">My Projects</button>
        <button class="btn btn-success" onclick="window.location.href='{{ route('project.create') }}'">Create Project</button>
        <button class="btn btn-success" onclick="window.location.href='{{ route('pending.invites') }}'">Pendent Invites</button>
    </div>
    
    
    
</body>
</html>
@endsection