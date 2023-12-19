@extends('layouts.app')

@section('content')

<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>

<p class="d-inline-flex gap-1">
    <button type="button" class="btn btn-primary" onclick="filterProjects('all')">All Projects</button>
    <button type="button" class="btn btn-primary" onclick="filterProjects('favorites')">Favorite Projects</button>
    <button type="button" class="btn btn-primary" onclick="filterProjects('archived')">Archived</button>
    <button type="button" class="btn btn-primary" onclick="filterProjects('leader')">Leader Project</button>
    <button type="button" class="btn btn-primary" onclick="filterProjects('member')">Member Project</button>
  </p>
  

<div id="allProjects">
    <h1>All My Projects</h1>
    <ul>
        @foreach($projects as $project)
            <li class="project">
                <a href="{{ route('task.show', ['title' => $project->title]) }}">
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
            <li class="project favorite">
                <a href="{{ route('task.show', ['title' => $favorite->title]) }}">
                    {{ $favorite->title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div id="archived" style="display: none;">
    <h1>Archived Projects</h1>
    <ul>
        @foreach($projects as $project)
            @if($project->archived)
                <li class="project archived">
                    <a href="{{ route('task.show', ['title' => $project->title]) }}">
                        {{ $project->title }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>


<div id="leader" style="display: none;">
    <h1>Leader Projects</h1>
    <ul>
        @foreach($projects as $project)
            @if($project->leaders->contains(Auth::user()->id) && !$project->archived)
                <li class="project leader">
                    <a href="{{ route('task.show', ['title' => $project->title]) }}">
                        {{ $project->title }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>



<div id="member" style="display: none;">
    <h1>Member Projects</h1>
    <ul>
        @foreach($projects as $project)
            @if(!$project->leaders->contains(Auth::user()->id) && $project->members->contains(Auth::user()->id) && !$project->archived)
                <li class="project leader">
                    <a href="{{ route('task.show', ['title' => $project->title]) }}">
                        {{ $project->title }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<script>
    function filterProjects(filter) {
        const allProjects = document.getElementById('allProjects');
        const favoriteProjects = document.getElementById('favoriteProjects');
        const archivedProjects = document.getElementById('archived');
        const leaderProjects = document.getElementById('leader');
        const memberProjects = document.getElementById('member');

        if (filter === 'favorites') {
            allProjects.style.display = 'none';
            favoriteProjects.style.display = 'block';
            archivedProjects.style.display = 'none';
            leaderProjects.style.display='none';
            memberProjects.style.display='none';
        } else if (filter === 'archived') {
            allProjects.style.display = 'none';
            favoriteProjects.style.display = 'none';
            archivedProjects.style.display = 'block';
            leaderProjects.style.display='none';
            memberProjects.style.display='none';

        } else if(filter === 'leader'){
            allProjects.style.display = 'none';
            favoriteProjects.style.display = 'none';
            archivedProjects.style.display = 'none';
            leaderProjects.style.display='block';
            memberProjects.style.display='none';
        }
        else if(filter==='member'){
            allProjects.style.display = 'none';
            favoriteProjects.style.display = 'none';
            archivedProjects.style.display = 'none';
            leaderProjects.style.display='none';
            memberProjects.style.display='block';

        }else {
            allProjects.style.display = 'block';
            favoriteProjects.style.display = 'none';
            archivedProjects.style.display = 'none';
            leaderProjects.style.display='none';
            memberProjects.style.display='none';
        }
    }
</script>

@endsection
