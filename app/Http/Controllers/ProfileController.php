<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function editProfileForm()
    {
        return view('pages.EditProfile');
    }

    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'name' => 'required',
            'birthdate' => 'date',
            'profile_pic' => 'required',
        ]);
    
        // Edite o perfil com os dados do formulário
        $profile = new User();
      
        $profile->username = $request->input('username');
        $profile->email = $request->input('email');
        $profile->name = $request->input('name');
        $profile->birthdate = $request->input('birthdate');
        $profile->profile_pic = $request->input('profile_pic');
        $profile->is_banned = false;
        $profile->save();
    }
    

    public function showProfileForm($username)
    {
        $profile = User::where('username', $username)->first();
    
        if (!$profile) {
            abort(404);
        }
    
        return view('pages.profile', compact('profile'));
    }
}
