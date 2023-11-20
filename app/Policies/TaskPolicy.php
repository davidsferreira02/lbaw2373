<?php




namespace App\Policies;

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

 
        use HandlesAuthorization;


        public function create(User $user, Project $project){
            return true;
            
}
}