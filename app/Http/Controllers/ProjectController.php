<?php

namespace App\Http\Controllers;


use App\Models\Favorite;
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
        
        // Obter todos os projetos do usuário
        $projects = Project::whereHas('members', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })->orWhereHas('leaders', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })->get();
    
        // Obter apenas os projetos favoritos do usuário
        $favoriteProjects = $user->favoriteProjects()->get();
    
        return view('pages.myProject', compact('projects', 'favoriteProjects'));
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
        $this->authorize('create', Project::class);
        return view('pages.createProject');
    }

   
public function showaddMemberForm($title)
{
    $project = Project::where('title', $title)->first();
    $this->authorize('addMemberorLeader',$project);
    return view('pages.addMember', compact('project'));
}

public function showaddLeaderForm($title)
{
    $project = Project::where('title', $title)->first();
    $this->authorize('addMemberorLeader',$project);
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
        $this->authorize('create', Project::class);
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
            return redirect()->back()->withErrors('Project not found');
        }
    
        // Verifique se o usuário autenticado é membro ou líder do projeto
       
        $user = Auth::user();
        $isLeader = $project->leaders->contains($user);
        $isFavorite = Favorite::where('project_id', $project->id)
        ->where('users_id', $user->id)
        ->exists();
    
        return view('pages.project', compact('project', 'isLeader','isFavorite'));
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
    $this->authorize('addMemberorLeader',$project);
   

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

        
        

        return view('pages.pedingInvites', compact('pendingInvites'));
}

public function acceptInvite($userId,$projectId)
{
   
   Invite::where('id_user', $userId)
    ->where('id_project', $projectId)
    ->update(['acceptance_status' => 'Accepted']);

   

 
    $project = Project::where('id', $projectId)->first();

    $this->addProjectMember($userId, $projectId);

    Invite::where('id_user', $userId)
    ->where('id_project', $projectId)
    ->delete();


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
Invite::where('id_user', $userId)
    ->where('id_project', $projectId)
    ->delete();

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

    $isFavorite = Favorite::where('project_id', $project->id)
    ->where('user_id', $user->id)
    ->exists();

    
    return view('pages.project', compact('project','isFavorite'));


}

public function showMembers($title)
{
    $project = Project::where('title', $title)->first();
    $members = $project->members;
    
    return view('pages.members', compact('project', 'members'));
}
public function showLeaders($title)
{
    $project = Project::where('title', $title)->first();
    $leaders = $project->leaders;
    

    return view('pages.leaders', compact('project', 'leaders'));
}



public function edit($title)
{

    $project = Project::where('title', $title)->first();
    $this->authorize('edit', $project);

    return view('pages.editProject', compact('project'));
}

public function update(Request $request,$title)
{

    $project = Project::where('title', $title)->first();
    $this->authorize('update', $project);
    
    $validatedData = $request->validate([
        'title' => 'required|string|max:255|unique:project,title,' . $project->id,
        'description' => 'required|string|max:255',
        'theme' => 'required|string|max:255'
        
    ]);


    $project->title=$request->input('title');
    $project->description=$request->input('description');
    $project->theme=$request->input('theme');
   
    



   

    $project->save();

    return redirect()->route('project.show', $project->title)->with('success', 'Projeto atualizado com sucesso!');
}


public function favorite($title){
    $user=Auth::user();
    $project = Project::where('title', $title)->first();
    $favorite = new Favorite();
    $favorite->users_id=$user->id;
    $favorite->project_id=$project->id;
   // $user->favoriteProjects()->attach($project->id);
    $favorite->save();

    return redirect()->route('project.show', $project->title)->with('success', 'Projeto atualizado com sucesso!');

}



public function noFavorite($title){
    $user = Auth::user();
    $project = Project::where('title', $title)->first();

    $favorite = Favorite::where('users_id', $user->id)
        ->where('project_id', $project->id)
        ->first();

    if ($favorite) {
        $favorite->delete();
    }

    return redirect()->route('project.show', $project->title)->with('success', 'Projeto desfavoritado com sucesso!');
}





public function archived($title)
{
    $project = Project::where('title', $title)->first();

    if ($project) {
        $project->archived = !$project->archived; 
        $project->save();
        return redirect()->route('project.show', $project->title)->with('success', 'Projeto atualizado com sucesso!');
    }

    return redirect()->back()->with('error', 'Projeto não encontrado');
}

}
