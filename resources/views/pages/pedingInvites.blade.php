@extends('layouts.app')

@section('content')
    <h1>Convites Pendentes</h1>

    @if ($pendingInvites->isEmpty())
        <p>Não há convites pendentes no momento.</p>
    @else
        <ul>
            @foreach ($pendingInvites as $invite)
                <li>
                    Você recebeu um convite para o projeto: {{ $invite->project->title }}
                    <form action="{{ route('accept.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                        @csrf
                        <button type="submit">Aceitar</button>
                    </form>
                    <form action="{{ route('decline.invite', ['id_user' => $invite->id_user, 'id_project' => $invite->id_project]) }}" method="POST">
                        @csrf
                        <button type="submit">Recusar</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
