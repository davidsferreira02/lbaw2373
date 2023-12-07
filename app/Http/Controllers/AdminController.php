<?php

namespace App\Http\Controllers;


use App\Models\Project;

use App\Models\User;



class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $projects = Project::all();

        return view('pages.admin', compact('users', 'projects'));
    }


    public function block($id)
    {
        $user = User::findOrFail($id);




        if ($user) {
            $user->isblocked = !$user->isblocked;
            $user->save();

        }
        return redirect()->route('profile', $user->id)->with('success', 'Projeto desfavoritado com sucesso!');
    }
}
