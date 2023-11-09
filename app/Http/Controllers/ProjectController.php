<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
{
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
    // Lógica para criar o projeto com os dados do formulário aqui.

    // Após criar o projeto, você pode redirecionar para a página desejada, por exemplo:
    return redirect()->route('project.store')->with('success', 'Projeto criado com sucesso.');
}


 public function create(Request $request)
{
    
    $project = new Project();
    $owner=new Owner();

    
    $this->authorize('create', $project);

    // Set card details.
    $project->title=$request->input('title');
    $project->description = $request->input('description');
    $project->theme=$request->input('theme');
    $project->archived=false;
    $ownerId = Auth::user()->id;
    $owner = $this->addProjectLeader($ownerId, $project->id);
    $project->owners()->save($owner);
    
    $this->addProjectMember($ownerId, $project->id);
    // Save the card and return it as JSON.
    $project->save();
    return redirect()->route('project.show')->with('success', 'Projeto criado com sucesso!');
}

public function addProjectMember($idUser, $idProject)
{

    DB::table('isMember')->insert(
        ['id_user' => $idUser, 'id_project' => $idProject]
    );
}

public function addProjectLeader($idUser, $idProject)
    {
        /*
            This is not an api endpoint. It's called in another function that grantes the correct policy
            Hence this does not need a Policy
        */

        $owner = new Owner();

        $owner->id_user = $idUser;
        $owner->id_project = $idProject;

        return $owner;
    }

    public function show($title)
    {
        // Supondo que 'title' seja um campo único na tabela de projetos
        $projects = Project::where('title', $title)->first();
    
        // Verifique se o projeto foi encontrado
        if (!$projects) {
            abort(404); // Projeto não encontrado, retorne uma resposta 404
        }
    
        return view('pages.project', compact('projects'));
    }
}