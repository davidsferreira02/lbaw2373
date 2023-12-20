@extends('layouts.app')

@section('content')

<style>
    /* Styles for user cards */
    .user-card {
        width: calc(100% - 40px);
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: background-color 0.3s ease; /* Smooth transition */
    }


    #usersContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        width: calc(50% - 20px); /* 50% width for users */
        float: left; /* Float left to position on the left side */
    }

    /* Styles for project cards */
    .project-card {
        width: calc(100% - 40px);
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: background-color 0.3s ease; /* Smooth transition */
    }

    .projectsContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        width: calc(50% - 20px); /* 50% width for projects */
        float: right; /* Float right to position on the right side */
    }

    /* Anchor style for clickable project cards */
    .project-link {
        display: block;
        text-decoration: none;
        color: inherit; /* Inherit the text color */
        width: 100%; /* Make sure the anchor fills the entire project card */
        height: 100%; /* Make sure the anchor fills the entire project card */
    }

    /* Hover effect for user cards and project cards */
    .user-card:hover,
    .project-card:hover {
        background-color: #f0f0f0; /* Change to your preferred hover color */
    }

    .userTitle{
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        width: calc(50% - 20px); /* 50% width for projects */
        float: left; /* Float right to position on the right side */
    }
    .projectTitle{
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        width: calc(50% - 20px); /* 50% width for projects */
        float: right; /* Float right to position on the right side */
    }
</style>

<h2>Admin Dashboard</h2>

<!-- Conteúdo específico da área de administração -->
<p>Welcome to the administration area!</p>

<div id="titleContainer">
    <div class="userTitle">
        <h3>Users:</h3>
    </div>
    <div class="projectTitle">
        <h3>Projects:</h3>
    </div>
</div>

<div id="usersContainer">
    @foreach($users as $index => $user)
        <div class="user-card">
            <a href="{{ route('profile', ['id' => $user->id]) }}" class="card user-details-box">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->username }}</h5>
                    @if($user->isAdmin())
                    <i class="fa-solid fa-user-tie"></i>
                    @endif
                    <p class="card-text">{{ $user->email }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="projectsContainer">
    @foreach($projects as $project)
        <div class="project-card">
            <a href="{{ route('task.show', ['title' => $project->title]) }}" class="project-link">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->title }}</h5>
                    <p class="card-text">{{ $project->description }}</p>
                    <!-- You can add other project details here -->
                </div>
            </a>
        </div>
    @endforeach
</div>

@endsection
