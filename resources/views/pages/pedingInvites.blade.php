@extends('layouts.app')

@section('content')
    <h1>Pending invitations</h1>

    @if ($pendingInvites->isEmpty())
        <p>No invitations pending at the moment.</p>
    @else
        <ul>
            @foreach ($pendingInvites as $invite)
                <li>
                    There are no invitations pending at the moment: {{ $invite->project->title }}
                    <form action="{{ route('accept.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                        @csrf
                        <button type="submit">Accept</button>
                    </form>
                    <form action="{{ route('decline.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                        @csrf
                        <button type="submit">Refuse</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('project.show', ['title' => $project->title]) }}" class="btn btn-primary">Go back</a>
@endsection
