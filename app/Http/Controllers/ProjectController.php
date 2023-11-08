<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Project;
use Illuminate\Http\Request;

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
    $owner->id_user=Auth::user()->id;
    $owner->id_project=$project->id;
    

    // Save the card and return it as JSON.
    $project->save();
    return response()->json($project);
}


}


