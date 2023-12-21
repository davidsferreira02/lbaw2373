<?php

namespace App\Policies;
use App\Models\Comment;
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
        return !$user->isblocked && $project->members()->where('id_user', $user->id)->count() > 0 && !$user->isAdmin() ;
    }


    public function edit(User $user, Project $project, Task $task, Comment $comment)
    {
        $owner = $comment->owner()
            ->wherePivot('id_comment', $comment->id)
            ->wherePivot('id_user', $user->id)
            ->first();
    
        if (!$owner) {
            return !$user->isblocked && $project->leaders()->where('id_user', $user->id)->count() > 0 && !$user->isAdmin();
        }
    
        
        return !$user->isblocked && ($owner->id === Auth::user()->id )&& !$user->isAdmin();
    }
    
  
    public function delete(User $user, Project $project, Task $task,Comment $comment){
      
        $owner = $comment->owner()
        ->wherePivot('id_comment', $comment->id)
        ->wherePivot('id_user', $user->id)
        ->first();

    if (!$owner) {
        return !$user->isblocked && $project->leaders()->where('id_user', $user->id)->count() > 0 &&  !$user->isAdmin();
    }

    
    return !$user->isblocked && ($owner->id === Auth::user()->id)  && !$user->isAdmin();
    }

    public function show (User $user, Project $project, Task $task){
        return !$user->isblocked && $project->members()->where('id_user', $user->id)->count() > 0 || $user->isAdmin(); 
    }
}
        
    
