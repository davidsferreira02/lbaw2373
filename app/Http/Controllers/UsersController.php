<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
   
   
    public function search(Request $request)
    {
        $search = $request->input('search');
        $this->authorize('search', User::class);
    
        $users = [];
        $projects = [];
    
        if ($search) {
            if (Auth::user()->isAdmin()) {
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
                $userId = Auth::user()->id;
                $users = User::whereRaw('search @@ plainto_tsquery(\'english\', ?)', [$search])
                    ->orWhere('name', 'ILIKE', '%' . $search . '%')
                    ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$search])
                    ->limit(40)
                    ->get();
    
                $projects = Project::whereHas('members', function ($query) use ($userId, $search) {
                        $query->where('id_user', $userId);
                    })
                    ->where(function ($query) use ($search) {
                        $query->whereRaw('search @@ plainto_tsquery(\'english\', ?)', [$search])
                            ->orWhere('title', 'ILIKE', '%' . $search . '%');
                    })
                    ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$search])
                    ->limit(40)
                    ->get();
            }
        } else {
            if(Auth::user()->isAdmin()){
                $users = User::all();
                $projects = Project::all();
            }
            else{
                $users = User::all();
                $user = Auth::user();
              
               $projects = $user->projectMember()->get();

            }
          
        }
    
        return view('pages.search_results', compact('users', 'projects', 'search'));
    }
}    
    
