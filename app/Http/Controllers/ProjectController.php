<?php

namespace App\Http\Controllers;


use App\Models\Project;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
{
    $user = Auth::user();
    
    $projects = Project::whereHas('members', function ($query) use ($user) {
        $query->where('id_user', $user->id);
    })->orWhereHas('leaders', function ($query) use ($user) {
        $query->where('id_user', $user->id);
    })->get();

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

   
public function showaddMemberForm($title)
{
    $project = Project::where('title', $title)->first();
    return view('pages.addMember', compact('project'));
}

public function showaddLeaderForm($title)
{
    $project = Project::where('title', $title)->first();
    return view('pages.addLeader', compact('project'));
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
      
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->theme = $request->input('theme');
        $project->archived = false;
       
        $project->save();
       
        
      
        $this->addProjectLeader(Auth::user()->id, $project->id);
        $this->addProjectMember(Auth::user()->id, $project->id);
      
  
    
        // Redirecione o usuário após criar o projeto
        return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Projeto criado com sucesso!');

    }
    


    
   
    
    public function show($title)
    {
        // Supondo que 'title' seja um campo único na tabela de projetos
        $project = Project::where('title', $title)->first();
    
        // Verifique se o projeto foi encontrado
        if (!$project) {
            abort(404); // Projeto não encontrado, retorne uma resposta 404
        }
    
        // Verifique se o usuário autenticado é membro ou líder do projeto
        $user = Auth::user();
        if (!$project->members->contains($user) && !$project->leaders->contains($user)) {
            abort(403); // Usuário não tem permissão para visualizar este projeto, retorne uma resposta 403 (Proibido)
        }
    
        return view('pages.project', compact('project'));
    }
    

    public function countProjectMembers($projectId)
{
    // Faça uma consulta para contar o número de membros associados ao projeto
    $count = DB::table('is_member')->where('id_project', $projectId)->count();

    return $count;
}

public function countProjectLeaders($projectId)
{
    // Faça uma consulta para contar o número de membros associados ao projeto
    $count = DB::table('is_leader')->where('id_project', $projectId)->count();

    return $count;
}

public function addProjectLeader($userId,$projectId){
    DB::table('is_leader')->insert([
        'id_user' => $userId,
        'id_project' => $projectId,
    ]);

}
public function addProjectMember($userId,$projectId){
    DB::table('is_member')->insert([
        'id_user' => $userId,
        'id_project' => $projectId,
    ]);
}

public function addOneMember(Request $request,$title){
    $validatedData = $request->validate([
        'username' => 'required|max:255'
    ]);
    
    $username = $request->input('username');
    $user = User::where('name', $username)->first();
    if (!$user) {
        abort(404); 
    }
    $project = Project::where('title', $title)->first();
    if (!$project) {
        abort(404); 
    }

   $this->sendInvite($user->id, $project->id);

  
   return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Convite enviado com sucesso!');


}

public function sendInvite($userId,$projectId){
    DB::table('inviteproject')->insert([
        'id_user' => $userId,
        'id_project' => $projectId,
    ]);
}

public function pendingInvite()
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

public function acceptInvite($userId,$projectId)
{
   
    $invite = Invite::where('id_user', $userId)
    ->where('id_project', $projectId)
    ->first();

    $invite->acceptance_status='Accepted';

    $invite->save();
 
    $project = Project::where('id', $projectId)->first();

    $this->addProjectMember($userId, $projectId);

    // Lógica adicional para adicionar o usuário ao projeto (se necessário)
    return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Convite aceito com sucesso!');


}

public function declineInvite($userId,$projectId)
{
    $invite = Invite::where('id_user', $userId)
    ->where('id_project', $projectId)
    ->first();

$invite->acceptance_status = 'Declined';
$invite->save();

    // Lógica adicional para adicionar o usuário ao projeto (se necessário)
    return redirect()->route('pages.home', compact('project'))->with('success', 'Convite recusado com sucesso!');
}





public function addOneLeader(Request $request,$title){
    $validatedData = $request->validate([
        'username' => 'required|max:255'
    ]);
    
    $username = $request->input('username');
    $user = User::where('name', $username)->first();
    if (!$user) {
        abort(404); 
    }
    $project = Project::where('title', $title)->first();
    if (!$project) {
        abort(404); 
    }
   
    if (!$project->members()->where('id_user', $user->id)->exists()) {
        abort(403, 'Usuário não é um membro do projeto.'); 
    }
    else{
        $this->addProjectLeader($user->id,$project->id);
    }

    
    return view('pages.project', compact('project'));


}




}