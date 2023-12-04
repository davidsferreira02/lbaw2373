@extends('layouts.app')

@section('content')

<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
    
</a>

    <select id="projectFilter">
        <option value="all">All Projects</option>
        <option value="favorites">Favorite Projects</option>
        <option value=archived> Archived</option>
    </select>

    <div id="allProjects">
        <h1>All My Projects</h1>
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

    <div id="archived" style="display: none;">
        <h1> Archived Projects </h1>
        <ul>
            @foreach($projects as $projects)
            @if($project->archived)
            <li>
                <a href="{{ route('project.show', ['title' => $project->title]) }}">
                    {{ $project->title }}
                </a>
            </li>
            @endif
            @endforeach
        </ul>
    </div>
        

    <!-- Botão para voltar para a página inicial (home) -->

    <script>
        document.getElementById('projectFilter').addEventListener('change', function() {
            var filter = this.value;

            if (filter === 'favorites') {
                document.getElementById('allProjects').style.display = 'none';
                document.getElementById('favoriteProjects').style.display = 'block';
                document.getElementById('archived').style.display='none';
            }
            else if(filter==='archived'){
                document.getElementById('allProjects').style.display = 'none';
                document.getElementById('favoriteProjects').style.display = 'none';
                document.getElementById('archived').style.display='block';
                
            } else {
                document.getElementById('allProjects').style.display = 'block';
                document.getElementById('favoriteProjects').style.display = 'none';
                document.getElementById('archived').style.display='none';
            }
        });
    </script>
@endsection
