@extends('layouts.app')

@section('content')
    <select id="projectFilter">
        <option value="all">All Projects</option>
        <option value="favorites">Favorite Projects</option>
    </select>

    <div id="allProjects">
        <h1>All Projects</h1>
        <ul>
            @foreach($projects as $project)
                <li>
                    <a href="{{ route('project.show', ['title' => $project->title]) }}">
                        {{ $project->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div id="favoriteProjects" style="display: none;">
        <h1>Favorite Projects</h1>
        <ul>
            @foreach($favoriteProjects as $favorite)
                <li>
                    <a href="{{ route('project.show', ['title' => $favorite->title]) }}">
                        {{ $favorite->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Botão para voltar para a página inicial (home) -->
    <a href="{{ route('project.home') }}" class="btn btn-primary">Go back</a>

    <script>
        document.getElementById('projectFilter').addEventListener('change', function() {
            var filter = this.value;

            if (filter === 'favorites') {
                document.getElementById('allProjects').style.display = 'none';
                document.getElementById('favoriteProjects').style.display = 'block';
            } else {
                document.getElementById('allProjects').style.display = 'block';
                document.getElementById('favoriteProjects').style.display = 'none';
            }
        });
    </script>
@endsection
