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
 


 

     public function delete(User $user, Task $task)
     {

        return $task->owners->contains($user);
     }
   
     public function edit(User $user,Task $task){
        return $task->owners->contains($user);
     }

     public function show(User $user, Project $project, Task $task){
        return $project->members()->where('id_user', $user->id)->count() > 0;
     }

}
