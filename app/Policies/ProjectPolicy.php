<?php




namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Project $project)
    {
        // Defina aqui a lógica de autorização para visualização
    }

    public function create(User $user)
    {
        // Defina aqui a lógica de autorização para criação
    }

    public function update(User $user, Project $projectt)
    {
        // Defina aqui a lógica de autorização para atualização
    }

    public function delete(User $user, Project $project)
    {
        // Defina aqui a lógica de autorização para exclusão
    }
}
