<?php




namespace App\Policies;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Project $project)
    {
        // Defina aqui a lógica de autorização para visualização
    }

    public function create(User $user,Request $request)
    {
        return Project::where('title', '=', $request->input('tile'))->firstOrFail() === null; // IT has only to be authenticated ... and can't have a group with the same name
    }

    public function update(User $user, Project $project)
    {
        return \DB::table('isLeader')
        ->where('id_user', $user->id)
        ->where('id_project', $project->id)
        ->exists();
    }

    
        public function delete(User $user, Project $project)
{
    // Verifique se o usuário é um líder do projeto
    return \DB::table('isLeader')
        ->where('id_user', $user->id)
        ->where('id_project', $project->id)
        ->exists();
}

    }