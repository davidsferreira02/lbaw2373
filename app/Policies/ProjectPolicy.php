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

  


    public function home(User $user) //home page
    {
        return !$user->isblocked && Auth::check() && !$user->isAdmin();
    }

    public function myProject(User $user){ // myProjectPage
        return !$user->isblocked && Auth::check() && !$user->isAdmin();
    }
  

    public function create(User $user) //createProjectForm
    {
        return !$user->isblocked && Auth::check() && !$user->isAdmin() ; 
    }

    public function store(User $user) //storeProjectForm
    {
        return !$user->isblocked && Auth::check() && !$user->isAdmin() ; 
    }

    public function addMember(User $user,Project $project){ //addMember

       return !$user->isblocked && $project->leaders->contains($user) && Auth::check() && !$user->isAdmin();
    }

    public function addLeader(User $user,Project $project){ //addLeader

        return !$user->isblocked && $project->leaders->contains($user) && Auth::check() && !$user->isAdmin();
     }


     public function show(User $user){ //see project page
        return !$user->isblocked && Auth::check();

     }

     public function deleteMember(User $user,Project $project){ //deleteMember

        return !$user->isblocked && $project->leaders->contains($user) && Auth::check()&& !$user->isAdmin();

     }

     public function sendInvite(User $user,Project $project){ //send Invite
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check()&& !$user->isAdmin();
     }
    public function pendingInvite(User $user){ //see pending Invites
        return !$user->isblocked && Auth::check()&& !$user->isAdmin();
    }

    public function declineInvite(User $user){ //Decline Invite
        return !$user->isblocked && Auth::check()&& !$user->isAdmin();
    }
    public function acceptInvite(User $user){ //Accept Invite
        return !$user->isblocked && Auth::check()&& !$user->isAdmin();
    }

    public function showMembers(User $user){ //show members page
        return !$user->isblocked && Auth::check();

    }
    public function showLeaders(User $user){ //show Leaders page
        return !$user->isblocked && Auth::check();

    }

    public function edit(User $user,Project $project){ //edit Project
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check() || $user->isAdmin();

    }

    public function update(User $user,Project $project){ //update Project
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check() || $user->isAdmin();

    }

    public function delete(User $user,Project $project){ // delete Project
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check() || $user->isAdmin();

    }

    public function favorite(User $user,Project $project){ //favorite

        return !$user->isblocked && $project->members->contains($user) && Auth::check();


    }


    public function noFavorite(User $user,Project $project){ //No favorite

        return !$user->isblocked && $project->members->contains($user) && Auth::check();


    }

    public function archived(User $user,Project $project){ //archived
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check();
    }


    public function searchByUsernameAddMember(User $user,Project $project) { //search user for member
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check();
    }
    
    public function searchByUsernameAddLeader(User $user,Project $project) {//search user for leader
        return !$user->isblocked && $project->leaders->contains($user) && Auth::check();
    }
    
    public function leaveProject(User $user,Project $project) //leave project
    {
        return !$user->isblocked && $project->members->contains($user) && Auth::check();
       
    }
    
  
    


    
   
}
