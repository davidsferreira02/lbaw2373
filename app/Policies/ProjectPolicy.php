<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\ProjectController;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ProjectPolicy
{
    use HandlesAuthorization;

  

  

    public function create()
    {
        return Auth::check(); // Retorna true se o usuário estiver autenticado
    }
    public function show(User $user, Project $project)
    {
        return $project->leaders->contains($user) || $project->members->contains($user);
    }
   
    public function addMemberorLeader(User $user, Project $project)
    {
        return $project->leaders->contains($user) ;
    }


    public function edit(User $user,Project $project){
        return $project->leaders->contains($user);
    }
    public function update(User $user,Project $project){
        return $project->leaders->contains($user);
    }
   
}
