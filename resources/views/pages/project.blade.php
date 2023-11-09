
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $projects->title }}</h1>
        <p><strong>Description:</strong> {{ $projects->description }}</p>
        <p><strong>Theme:</strong> {{ $projects->theme }}</p>

        <!-- Adicione mais informações conforme necessário -->

        <a href="{{ route('project.index') }}" class="btn btn-primary">Back to Projects</a>
    </div>
@endsection
