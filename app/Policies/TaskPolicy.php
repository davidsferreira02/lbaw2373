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

    

}
