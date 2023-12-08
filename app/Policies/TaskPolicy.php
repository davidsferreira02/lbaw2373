<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
 




    public function create(User $user, Project $project)
    {
      
        return !$user->isblocked && $user->projectLeader->contains($project) || $user->projectMember->contains($project);
    }

     public function delete(User $user, Task $task)
     {

        return !$user->isblocked && $task->owners->contains($user);
     }
   
     public function edit(User $user,Task $task){
        return !$user->isblocked && $task->owners->contains($user);
     }

    

}
