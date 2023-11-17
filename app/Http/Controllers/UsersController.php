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
            $users = User::where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($search) . '%')->get();
            $projects = Project::where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower($search) . '%')->get();
        } else {
            $users = User::all();
            $projects=Project::all();

        }
    
        return view('pages.search_results', ['users' => $users,'projects'=>$projects, 'search' => $search]);
    }
    public function pendingInvites()
{
    $userId = auth()->user()->id; 

    
    
    $pendingInvites = Invite::where('id_user', $userId)
        ->where('acceptance_status', 'Pendent')
        ->with('project') 
        ->get();

        if ($pendingInvites->isEmpty()) {
            // Não há convites pendentes, redirecionar ou retornar uma mensagem
           abort(404);
        }
    
    
    return view('pages.pedingInvites', compact('pendingInvites'));
}

}