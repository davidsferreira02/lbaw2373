@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $project->title }}</h1>
        <p><strong>Description:</strong> {{ $project->description }}</p>
        <p><strong>Theme:</strong> {{ $project->theme }}</p>
        <p><strong>Number of Members:</strong> @php echo app('App\Http\Controllers\ProjectController')->countProjectMembers($project->id); @endphp</p>
        <p><strong>Number of Leaders:</strong> @php echo app('App\Http\Controllers\ProjectController')->countProjectLeaders($project->id); @endphp</p>

        <!-- Adicione mais informações conforme necessário -->

        <a href="{{ route('project.index') }}" class="btn btn-primary">Back to Projects</a>
    </div>
@endsection
