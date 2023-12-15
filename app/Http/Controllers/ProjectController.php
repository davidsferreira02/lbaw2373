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
        $this->authorize('myProject', Project::class);
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
    $this->authorize('home', Project::class);
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
    $this->authorize('addMember',$project);
    return view('pages.addMember', compact('project'));
}

public function showaddLeaderForm($title)
{
    $project = Project::where('title', $title)->first();
    $this->authorize('addLeader',$project);
    return view('pages.addLeader', compact('project'));
}



    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|max:255|unique:project',
            'description' => 'required',
            'theme' => 'required',
        ]);
    
        // Crie o projeto com os dados do formulário
        $project = new Project();
      
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->theme = $request->input('theme');
        $project->archived = false;
        $this->authorize('store', Project::class);
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
        $this->authorize('show', Project::class);
     
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

public function addOneMember($title,$username)
{
   
    $project = Project::where('title', $title)->first();

    $user = User::where('username', $username)->first();
    if ($user) {
    $this->authorize('addMember',$project);
   

   $this->sendInvite($user->id, $project->id);

  
   return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Convite enviado com sucesso!');
    }


}



public function deleteMember($title,$id){
    $project = Project::where('title', $title)->first();
    $user = User::findOrFail($id);
    $this->authorize('deleteMember',$project);
    $isLeader = $project->leaders()->where('id', $user->id)->exists();
    if(!$isLeader){
    DB::table('is_member')
    ->where('id_user', $user->id)
    ->where('id_project', $project->id)
    ->delete();
    return redirect()->route('project.showMember', ['title' => $project->title])->with('success', 'Membro apagado!');
    }
    abort(404);
    
  

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

    $this->authorize('pendingInvite',Project::class);

    $pendingInvites = Invite::where('id_user', $userId)
        ->where('acceptance_status', 'Pendent')
        ->with('project') 
        ->get();

        
        

        return view('pages.pedingInvites', compact('pendingInvites'));
}

public function acceptInvite($userId,$projectId)
{
    $this->authorize('acceptInvite',Project::class);
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


    $this->authorize('declineInvite',Project::class);
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





public function addOneLeader($title,$username){
    $project = Project::where('title', $title)->first();

    $user = User::where('username', $username)->first();
    if ($user) {
    $this->authorize('addLeader',$project);
   

    $this->addProjectLeader($user->id,$project->id);

  
   return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Convite enviado com sucesso!');
    }

}

public function showMembers($title)
{
    $project = Project::where('title', $title)->first();
    $members = $project->members;
    $this->authorize('showMembers',Project::class);
    
    return view('pages.members', compact('project', 'members'));
}
public function showLeaders($title)
{
    $project = Project::where('title', $title)->first();
    $leaders = $project->leaders;
    $this->authorize('showLeaders', Project::class);
    

    return view('pages.leaders', compact('project', 'leaders'));
}




public function update(Request $request,$title)
{

    $project = Project::where('title', $title)->first();
    $this->authorize('update', $project);
    
    $validatedData = $request->validate([
        'title' => 'required|max:255|unique:project',
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
    $this->authorize('favorite', $project);
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
    $this->authorize('noFavorite', $project);

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
    $this->authorize('archived', $project);
    if ($project) {
        $project->archived = !$project->archived; 
        $project->save();


        return redirect()->route('project.show', $project->title)->with('success', 'Projeto atualizado com sucesso!');
    }

    return redirect()->back()->with('error', 'Projeto não encontrado');
}


public function searchByUsernameAddMember(Request $request, $title) {
    $validatedData = $request->validate([
        'username' => 'required|max:255'
    ]);

    $project = Project::where('title', $title)->first();
    $username = $request->input('username');
    $this->authorize('searchByUsernameAddMember', $project);


    if (!$username) {
        return redirect()->back()->withInput()->withErrors(['error' => 'Enter a username.']);
    }

    $userId = Auth::id(); // Obtém o ID do usuário autenticado

    // Busca usuários que correspondam ao username fornecido e não são membros deste projeto
    $users = User::where('id', '!=', $userId)
    ->whereDoesntHave('isAdminRelation') // Garante que não têm a relação de administração
    ->whereDoesntHave('projectMember', function ($query) use ($project) {
        $query->where('id_project', $project->id);
    })
    ->where(function ($query) use ($username) {
        $query->where('username', 'ILIKE', '%' . $username . '%')
            ->orWhereRaw('search @@ plainto_tsquery(\'english\', ?)', [$username]);
    })
    ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$username])
    ->limit(40)
    ->get();

    $users = $users->filter(function ($user) {
        return !$user->isAdmin();
    });
    // Retorne a view com os resultados da busca
    return view('pages.user_search_results', compact('users', 'project','username'));
}

public function searchByUsernameAddLeader(Request $request, $title) {
    $validatedData = $request->validate([
        'username' => 'required|max:255'
    ]);

    $project = Project::where('title', $title)->first();
    $username = $request->input('username');

    $this->authorize('searchByUsernameAddLeader', $project);

    if (!$username) {
        return redirect()->back()->withErrors('Enter a username.');
    }

    $userId = Auth::id(); // Obtém o ID do usuário autenticado

    // Busca usuários que correspondam ao username fornecido e são membros deste projeto
    $users = User::whereHas('projectMember', function ($query) use ($project) {
        $query->where('id_project', $project->id);
    })
    ->whereDoesntHave('projectLeader', function ($query) use ($project) {
        $query->where('id_project', $project->id);
    })
    ->where(function ($query) use ($username) {
        $query->where('username', 'ILIKE', '%' . $username . '%')
            ->orWhereRaw('search @@ plainto_tsquery(\'english\', ?)', [$username]);
    })
    ->orderByRaw('ts_rank(search, plainto_tsquery(\'english\', ?)) DESC', [$username])
    ->limit(40)
    ->get();

    // Retorne a view com os resultados da busca
    return view('pages.user_search_leader', compact('users', 'project','username'));
}

public function leaveProject($title)
{
    $project = Project::where('title', $title)->first();

    $this->authorize('leaveProject', $project);

    // Verifica se o usuário autenticado é um membro do projeto
    if ($project->members->contains(Auth::user())) {
        // Verifica se há mais de um membro e um líder no projeto
        if ($project->members()->count() > 1 && $project->leaders()->count() > 1) {
            $project->members()->detach(Auth::user()->id);
            $project->leaders()->detach(Auth::user()->id);
            return redirect()->route('project.home')->with('success', 'Você saiu do projeto com sucesso.');
        } else {
            if($project->members()->count()==1){
                $project->delete();
                return redirect()->route('project.home')->with('success', 'Você saiu do projeto com sucesso.');
                
            }
            else if($project->leaders()->count()==1){
                return redirect()->route('project.show', ['title' => $project->title])->with('error', 'Adiciona um leader ao projeto.');
            }
        }
    }

    return redirect()->route('project.show', ['title' => $project->title])->with('error', 'Você não é um membro deste projeto.');
}



}
