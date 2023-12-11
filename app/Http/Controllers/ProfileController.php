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
            'profile_image' => 'image|mimes:jpg,jpeg,png|max:2048'
            
        ]);
    
        // Atualização dos campos nome e email
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
    
            // Generate a unique filename for the image
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            // Store the file in the public/profile directory (adjust the storage path as needed)
            $filePath = $file->storeAs('profile', $fileName, 'public');
    
            $user->photo = $filePath; // Save the file path in the database
        }
        // Verificação e processamento da imagem, se houver
    
        // Salva as alterações no usuário
        $user->save();
    
        // Redireciona para a página do perfil com uma mensagem de sucesso
        return redirect()->route('profile', $user->id)->with('success', 'Perfil atualizado com sucesso!');
    }
    
    
    public function delete($userId) {
        $user = User::find($userId);
    
        if ($user) {
            // Encontra todos os projetos onde o usuário é membro
            $memberProjects = $user->projectMember;
    
            // Encontra todos os projetos onde o usuário é líder
            $leaderProjects = $user->projectLeader;
            $allProjects = [];

            if ($memberProjects) {
                $allProjects = $memberProjects;
            }
    
            if ($leaderProjects) {
                $allProjects = $allProjects->merge($leaderProjects);
            }
    
            // Itera sobre todos os projetos para remover associações do usuário
            foreach ($allProjects as $project) {
                $project->members()->detach($user->id);
                $project->leaders()->detach($user->id);
            }
    
            // Depois de remover associações, você pode excluir o usuário se necessário
            $user->delete();
    
            return redirect()->route('login')->with('success', 'Usuário e associações de projeto removidos com sucesso.');
        } else {
            return redirect()->route('login')->with('error', 'Usuário não encontrado.');
        }
    }

}