@extends('layouts.app')

@section('content')



<a href="{{ route('project.show' ,['title'=> $project->title]) }}" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> <!-- Use "fas" para ícones sólidos -->
</a>



<h1>Members from {{$project->title}} </h1>
    <ul>
        @foreach ($members as $member)
            <ul>
                <div>
                    <a href="{{ route('profile', ['id' => $member->id]) }}" class="btn btn-primary">{{ $member->username }}</a>
                    @if (!$project->leaders()->where('id_user', $member->id)->exists() && $member->id !== Auth::id() &&  $project->leaders()->where('id_user', Auth::id())->exists())
                        <form action="{{ route('project.deleteMember', ['id' => $member->id, 'title' => $project->title]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este membro?')">Delete Member From This Project</button>
                        </form>
                        
                    @endif
                    @if ($project->leaders()->where('id_user', $member->id)->exists())
                    <i class="fa-solid fa-crown"></i>
                    @endif
                </div>
            </ul>
        @endforeach
    </ul>

 
@endsection
