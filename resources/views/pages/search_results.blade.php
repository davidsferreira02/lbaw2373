@extends('layouts.app')

@section('content')
    <a href="{{ route('project.home') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i>
    </a>

    @if($search)
        <h1>Search results: {{ $search }}</h1>
    @endif

    <div class="row">
        <div class="col-sm-6">
            <button class="btn btn-primary active" onclick="filterResults('users')">Users</button>
            <ul id="usersList" style="display: block;">
         
                <h2>Users:</h2>
                @if(count($users) > 0)
                    @foreach($users as $user)
                        <li>
                            <button class="btn btn-primary" onclick="location.href='{{ route('profile', ['id' => $user->id,'project' => $user->projectMember()]) }}'">
                                {{ $user->name }}
                            </button>
                        </li>
                    @endforeach
                @else
                    <p>No users found.</p>
                @endif
            </ul>
        </div>
        <div class="col-sm-6">
            
            <button class="btn btn-primary" onclick="filterResults('projects')">Projects</button>
            <ul id="projectsList" style="display: none;">
          
                <h2>Projects:</h2>
                @if(count($projects) > 0)
                    @foreach($projects as $project)
                        <li>
                            <button class="btn btn-primary" onclick="location.href='{{ route('task.show', ['title' => $project->title]) }}'">
                                {{ $project->title }}
                            </button>
                        </li>
                    @endforeach
                @else
                    <p>No projects found.</p>
                @endif
            </ul>
        </div>
    </div>

    <script>
        function filterResults(type) {
            const usersList = document.getElementById('usersList');
            const projectsList = document.getElementById('projectsList');
            const usersButton = document.querySelector('[onclick="filterResults(\'users\')"]');
            const projectsButton = document.querySelector('[onclick="filterResults(\'projects\')]');

            if (type === 'users') {
                usersList.style.display = 'block';
                projectsList.style.display = 'none';
                usersButton.classList.add('active');
                projectsButton.classList.remove('active');
            } else if (type === 'projects') {
                usersList.style.display = 'none';
                projectsList.style.display = 'block';
                usersButton.classList.remove('active');
                projectsButton.classList.add('active');
            }
        }
    </script>
@endsection
