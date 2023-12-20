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
                There is a pending invitation to the project: {{ $invite->project->title }}
                <form action="{{ route('accept.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                    @csrf
                    <button type="submit" class="invite-button accept" onclick="return confirm('Tem certeza que deseja aceitar este convite?')">Accept</button>
                </form>
                <form action="{{ route('decline.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                    @csrf
                    <button type="submit" class="invite-button refuse" onclick="return confirm('Tem certeza que deseja recusar este convite?')">Refuse</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif

@endsection
