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
                            <button type="button" class="btn btn-danger deleteMemberButton" onclick="return confirm('Tem certeza que deseja excluir este membro?')" data-member-id={{ $member->id }}>Delete Member From This Project</button>
                        </form>



<!--...-->

                        
                    @endif
                    @if ($project->leaders()->where('id_user', $member->id)->exists())
                    <i class="fa-solid fa-crown"></i>
                    @endif
                </div>
            </ul>
        @endforeach
    </ul>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.deleteMemberButton');
    
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const memberId = this.getAttribute('data-member-id');
                    const projectTitle = '{{ $project->title }}'; // Obtendo o título do projeto do PHP
    
                    fetch(`/project/${projectTitle}/member/${memberId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao excluir membro');
                        }
                        // Se a exclusão for bem-sucedida, remova o elemento da lista
                        this.closest('ul').removeChild(this.parentNode.parentNode);
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
                });
            });
        });
    </script>
    
    

 
@endsection

