<?php

namespace App\Http\Controllers;


use App\Models\Project;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class ProfileController extends Controller
{


public function show($id)
    {

       
        $user = User::findOrFail($id);
        $this->authorize('see', User::class);
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
        
        // Validação dos campos nome e email
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'image' => 'image|mimes:jpg,jpeg,png|max:2048' // Change to 'image' instead of 'profile_image'
        ]);
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image'); // Access the file using file() method
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestImage->move(public_path('img/profile'), $imageName); // Move the uploaded file to the desired directory
            
            
            $user->photo = $imageName; 
        }
        
        $user->save();
   
        
        return redirect()->route('profile', $user->id)
            ->withErrors($validatedData)
            ->withInput();
    }
    
    
    public function delete($userId) {
        $user = User::find($userId);
    
        if ($user) {
            if($user->isAdmin() && Auth::user()->id !=$user->id){

  return redirect()->back()->with('error', 'Não pode expulsar admin');
            }


            // Anonimizar tarefas do usuário, se existirem
        if ($user->tasksOwned()->count() > 0) {
            $anonymousUserId = User::where('email', 'anonimo@example.com')->first()->id;
    
            foreach ($user->tasksOwned as $task) {
                
                $task->owners()->detach($user->id);
                $task->owners()->attach($anonymousUserId);
            }
        }
    
        // Anonimizar tarefas atribuídas ao usuário, se existirem
        if ($user->tasksAssigned()->count() > 0) {
            $anonymousUserId = User::where('email', 'anonimo@example.com')->first()->id;
    
            foreach ($user->tasksAssigned as $task) {
                $task->assigned()->detach($user->id);
                $task->assigned()->attach($anonymousUserId);
            }
        }
    
        // Anonimizar comentários do usuário, se existirem
        if ($user->ownedComments()->count() > 0) {
            $anonymousUserId = User::where('email', 'anonimo@example.com')->first()->id;
    
            foreach ($user->ownedComments as $comment) {
              
                $comment->owner()->detach($user->id);
                $comment->owner()->attach($anonymousUserId);
            }
        }
    
        if(!$user->isAdmin()){
        // Remover associações do usuário em projetos
        $memberProjects = $user->projectMember;
        $leaderProjects = $user->projectLeader;
        $allProjects = $memberProjects->merge($leaderProjects);
        foreach ($allProjects as $project) { // garantir a integredidade dos projectos
            if($project->members()->count()>1 && $project->leaders()->count()>1){
            $project->members()->detach($user->id);
            $project->leaders()->detach($user->id);
            }
            else if($project->members()->count()==1 && $project->leaders()->count()==1){
                    $project->members()->detach($user->id);
                    $project->leaders()->detach($user->id);
                    $project->delete();
                }
                else if($project->members->count()>1 && $project->leaders()->count()==1){
                    return redirect()->back()->with('error', 'Adicione um membro ao projeto ' . $project->title . ' para ter um líder.');
                }


        }


        // Anonimizar likes do usuário, se existirem
if ($user->likes()->count() > 0) {
    foreach ($user->likes as $like) {
        $like->delete();
    }
}

// Anonimizar favoritos do usuário, se existirem
if ($user->favorites()->count() > 0) {
    foreach ($user->favorites as $favorite) {
        $favorite->delete();
    }
}
        }




    
        // Depois de anonimizar os dados associados, você pode excluir o usuário
        $user->delete();
        if(Auth::user()->isAdmin() && $user->id !=Auth::user()->id){
            return redirect()->route('admin.dashboard')->with('success', 'Usuário e dados associados anonimizados com sucesso.');
        }
    
        return redirect()->route('login')->with('success', 'Usuário e dados associados anonimizados com sucesso.');
        } else {
            return redirect()->route('login')->with('error', 'Usuário não encontrado.');
        }
    }
    
    

}