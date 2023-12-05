<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Invite;

use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
   
    public function index()
    {
        return view('users.index');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
    
        if ($search) {
            $users = User::whereRaw('search @@ plainto_tsquery(\'english\', ?)', [$search])
                ->orWhere('name', 'ILIKE', '%' . $search . '%')
                ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$search])
                ->limit(40)
                ->get();
    
            $projects = Project::whereRaw('search @@ plainto_tsquery(\'english\', ?)', [$search])
                ->orWhere('title', 'ILIKE', '%' . $search . '%')
                ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$search])
                ->limit(40)
                ->get();
        } else {
            $users = User::all();
            $projects = Project::all();
        }
    
        return view('pages.search_results', ['users' => $users, 'projects' => $projects, 'search' => $search]);
    }
    



}