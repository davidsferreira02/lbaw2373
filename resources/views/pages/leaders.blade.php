

@extends('layouts.app') 

@section('content')


<a href="{{ route('project.show' ,['title'=> $project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->

</a>
    <h1>Leaders from {{ $project->title }}</h1>
    <ul>
        @foreach ($leaders as $leader)
         
            <button class="btn btn-primary" onclick="location.href='{{ route('profile', ['id' => $leader->id,'project' => $leader->projectLeader()]) }}'">
                {{ $leader->username }}
            </button>
        @endforeach
    </ul>


@endsection


