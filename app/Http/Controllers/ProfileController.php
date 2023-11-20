<?php

namespace App\Http\Controllers;


use App\Models\Project;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class ProfileController extends Controller
{


public function show($id)
    {
        $user = User::findOrFail($id);
        $project = Project::whereHas('members', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })->orWhereHas('leaders', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })->get();
        return view('pages.profile', compact('user','project'));
    }


    public function edit()
    {
        $user = Auth::user();
        return view('pages.editProfile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        
    if (!empty($name)) {
        $user->name = $name;
    }

    if (!empty($email)) {
        $user->email = $email;
    }
       

        $user->save();

        return redirect()->route('profile', $user->id)->with('success', 'Perfil atualizado com sucesso!');
    }   

}