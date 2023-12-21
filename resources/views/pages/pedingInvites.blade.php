@extends('layouts.app')

@section('content')
    <a href="{{ route('project.home') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h1>Pending invitations</h1>
    @if ($pendingInvites->isEmpty())
        <p>No invitations pending at the moment.</p>
    @else
        <ul class="invite-list">
            @foreach ($pendingInvites as $invite)
                <li class="invite-item">
                    <div class="invite-card">
                        There is a pending invitation to the project: {{ $invite->project->title }}
                        <form action="{{ route('accept.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                            @csrf
                            <button type="submit" class="invite-button accept" onclick="return confirm('Are you sure you want to accept this invitation?')">Accept</button>
                        </form>
                        <form action="{{ route('decline.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                            @csrf
                            <button type="submit" class="invite-button refuse" onclick="return confirm('Are you sure you want to reject this invitation?')">Refuse</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
