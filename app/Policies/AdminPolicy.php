<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\ProjectController;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AdminPolicy
{
    use HandlesAuthorization;

    public function index(User $user){
      return $user->isAdmin() && Auth::check();
    }

    public function block(User $user){
        return $user->isAdmin() && Auth::check();
    }
}
  