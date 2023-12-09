<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
 




    public function create(User $user, Project $project)
    {
      
        return !$user->isblocked && $user->projectMember->contains($project) && Auth::check();
    }

    public function store(User $user, Project $project)
    {
      
        return !$user->isblocked && $user->projectMember->contains($project)&& Auth::check();
    }
     public function delete(User $user, Task $task,Project $project)
     {

        return (!$user->isblocked && $task->owners->contains($user) && Auth::check()) || ($project->leaders->contains($user) &&!$user->isblocked);
     }
   
     public function edit(User $user,Task $task,Project $project){
      return (!$user->isblocked && $task->owners->contains($user) && Auth::check()) || ($project->leaders->contains($user) &&!$user->isblocked);
     }


     public function isCompleted(User $user,Project $project){

      return !$user->isblocked && $user->projectMember->contains($project)&& Auth::check() && !$user->idAmin();

     }

public function show(User $user,Project $project){
   return (!$user->isblocked && $user->projectMember->contains($project)&& Auth::check() )|| ($user->isAdmin() && Auth::check());


}
    

}
