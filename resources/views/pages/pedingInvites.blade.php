@extends('layouts.app')

@section('content')
<a href="{{ route('project.home') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
    
</a>
    <h1>Pending invitations</h1>

    @if ($pendingInvites->isEmpty())
        <p>No invitations pending at the moment.</p>
    @else
        <ul>
            @foreach ($pendingInvites as $invite)
                <li>
                    There is a pending invitation to the project: {{ $invite->project->title }}
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
@endsection
