@extends('layouts.app')

@section('content')
    <a href="{{ route('project.home') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h1>Pending invitations</h1>

    @if ($pendingInvites->isEmpty())
        <p>No invitations pending at the moment.</p>
    @else
        <div class="projects-container">
            @foreach ($pendingInvites as $invite)
                <div class="project-square">
                    <p>Project: {{ $invite->project->title }}</p>
                    <div class="button-container">
                        <form action="{{ route('accept.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Tem certeza que deseja aceitar este convite?')">Accept</button>
                        </form>
                        <form action="{{ route('decline.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Tem certeza que deseja recusar este convite?')">Refuse</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
