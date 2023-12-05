<?php

namespace App\Policies;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */

    use HandlesAuthorization;
   
   
    public function create(User $user, Project $project, Task $task)
    {
        return $project->members()->where('id_user', $user->id)->count() > 0;
    }

    public function edit(User $user, Project $project, Task $task){
        return $project->members()->where('id_user', $user->id)->count() > 0;

    }
    public function delete(User $user, Project $project, Task $task){
        return $project->members()->where('id_user', $user->id)->count() > 0;

    }
}
        
    
