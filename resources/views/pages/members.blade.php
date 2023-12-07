@extends('layouts.app') 

@section('content')
    <h1>Members from {{ $project->title }}</h1>
    <ul>
        @foreach ($members as $member)
            <form action="{{ route('project.deleteMember', ['id' => $member->id,'title'=>$project->title]) }}" method="POST">
                @csrf
                @method('DELETE')
                <li>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este membro?')">
                        {{ $member->name }} - Delete Member
                    </button>
                </li>
            </form>
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>
@endsection
