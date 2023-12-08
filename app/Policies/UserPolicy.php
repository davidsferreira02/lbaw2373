<?php

namespace App\Policies;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */

    use HandlesAuthorization;
   
   
    public function search(User $user)
    {
        return !$user->isblocked && Auth::check();
    }

    public function see(User $user)
    {
        return !$user->isblocked && Auth::check();
    }

 

}
        
    
