<?php




namespace App\Policies;

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

 
        use HandlesAuthorization;


        public function create(User $user, Project $project){
            return $project->leaders->contains($user) || $project->members->contains($user);
            
}
public function view(User $user, Project $project)
{
    return $project->leaders->contains($user) || $project->members->contains($user);
}

public function delete(Task $task)
{
    return $task->owners->contains(Auth::user()->id);
}
}