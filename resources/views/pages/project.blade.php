@extends('layouts.app')

@section('content')
    <div class="container">
        @isset($project)
        <h1>{{ $project->title }}</h1>
        <p><strong>Description:</strong> {{ $project->description }}</p>
        <p><strong>Theme:</strong> {{ $project->theme }}</p>
       
    @endisset
    
        <!-- Adicione mais informações conforme necessário -->

        <a href="{{ route('project.index') }}" class="btn btn-primary">Back to Projects</a>
    </div>
@endsection
