<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

use App\Http\Controllers\ProjectController;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;




class ProfilePolicy
{
    use HandlesAuthorization;

  

  

  public function update(User $user){
    return $user->id === Auth::user()->id;   
}

public function delete(User $user){
    return $user->id === Auth::user()->id;   
}
}

