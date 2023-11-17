
@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <title>Página Inicial</title>
</head>
<body>
    <h1>Minha Página Inicial</h1>

    <form action="{{ route('search.users') }}" method="GET">
        <input type="text" name="search" placeholder="Pesquisar users ou projetos">
        <button type="submit">Pesquisar</button>
    </form>

    <a href="{{ route('project.index') }}" class="btn btn-primary">My Projects</a>
    <a href="{{ route('project.create') }}" class="btn btn-success">Create Project</a>
</body>
</html>
@endsection