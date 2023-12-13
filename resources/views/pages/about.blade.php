

@extends('layouts.app')


@section('content')


@if(Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isblocked)
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(Auth::check() && Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(!Auth::check())
<a href="{{ route('login') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif

@if(Auth::check() && Auth::user()->isblocked )
<a href="{{ route('blocked') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>
@endif
    <div class="container">
        <h1>About Us</h1>
        <div class="list-group-item ">
            <h3 class="text-bg-light p-2"> What is TaskSquad?</h3>
            <article class="about_paragraph">TaskSquad is a platform that stands out in the world of project management. With it, you can create projects intuitively, establish specific tasks, comment on each one and maintain impeccable organisation in all aspects of a project. Whether it's initial structuring, defining stages or communication between team members, TaskSquad offers a complete experience for those looking for efficiency and clarity in the execution of their projects.</p>
            </article>
        </div>

        <div class="list-group-item ">
            <h3 class="text-bg-light p-2"> How did TaskSquad come about?</h3>
            <article class="about_paragraph">
                <p style="text-align: justify;"> TaskSquad came about because of our LBAW course project in 2023/2024.</p>
            </article>
        </div>


        <div class="list-group-item ">
            <h3 class="text-bg-light p-2"> Who we are?</h3>
            <article class="about_paragraph">
                <p style="text-align: justify;">We are 3rd year Software Engineering Students at the Faculty of Engineering
                    of the
                    University of Porto.</p>
            </article>
        </div>
        <!-- Add any other content or details about your team/company here -->
    </div>
@endsection
