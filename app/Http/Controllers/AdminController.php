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
}
