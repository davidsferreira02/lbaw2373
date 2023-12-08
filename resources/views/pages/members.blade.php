@extends('layouts.app')

@section('content')
    <h1>Members from {{ $project->title }}</h1>
    <ul>
        @foreach ($members as $member)
            <li>
                <div>
                    <a href="{{ route('profile', ['id' => $member->id]) }}" class="btn btn-primary">{{ $member->name }}</a>
                    @if (!$project->leaders()->where('id_user', $member->id)->exists())
                        <form action="{{ route('project.deleteMember', ['id' => $member->id, 'title' => $project->title]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este membro?')">Delete Member</button>
                        </form>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>
@endsection
