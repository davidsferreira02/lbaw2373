<?php

namespace App\Http\Controllers;

use App\Models\Leader;
use App\Models\Project;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
{
    $projects = Project::all(); // Isso busca todos os projetos na tabela 'projects'.

    return view('pages.myProject', compact('projects'));
}

public function home(){
    return view('pages.home');
}

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showCreateProjectForm()
    {
        return view('pages.createProject');
    }


    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'theme' => 'required',
        ]);
    
        // Crie o projeto com os dados do formulário
        $project = new Project();
        $leader=new Leader();
      
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->theme = $request->input('theme');
        $project->archived = false;
        $project->save();
        $project->member()->attach(Auth::user()->id);
        $project->leader()->attach(Auth::user()->id);
      
       
      
  
    
        // Redirecione o usuário após criar o projeto
        return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Projeto criado com sucesso!');

    }
    


    
    public function addProjectLeader($userId, $projectId)
    {
         
        DB::table('isLeader')->insert([
            'id_user' => $userId,
            'id_project' => $projectId
        ]);
       
    }
    
    public function show($title)
    {
        // Supondo que 'title' seja um campo único na tabela de projetos
        $project = Project::where('title', $title)->first();
    
        // Verifique se o projeto foi encontrado
        if (!$project) {
            abort(404); // Projeto não encontrado, retorne uma resposta 404
        }
    
        return view('pages.project', compact('project'));
    }

    public function countProjectMembers($projectId)
{
    // Faça uma consulta para contar o número de membros associados ao projeto
    $count = DB::table('isMember')->where('id_project', $projectId)->count();

    return $count;
}

public function countProjectLeaders($projectId)
{
    // Faça uma consulta para contar o número de membros associados ao projeto
    $count = DB::table('isLeader')->where('id_project', $projectId)->count();

    return $count;
}

}