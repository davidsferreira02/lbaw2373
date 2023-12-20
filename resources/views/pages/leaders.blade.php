

@extends('layouts.app') 

@section('content')


<a href="{{ route('project.show' ,['title'=> $project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->

</a>
    <h1>Leaders from {{ $project->title }}</h1>
  
        @foreach ($leaders as $leader)
            <ul>
                <div>
                    <a href="{{ route('profile', ['id' => $leader->id]) }}" class="btn btn-primary">{{ $leader->username }}</a>
                  
                   
                 
                    <i class="fa-solid fa-crown"></i>
                   
                </div>
            </ul>
        @endforeach
    </ul>


@endsection


