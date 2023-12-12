<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
class TaskController extends Controller
{
   

    public function show($title)
    {
        $project = Project::where('title', $title)->first();
    
        if (!$project) {
            abort(404); 
        }
    
      
       
        $tasks = Task::where('id_project', $project->id)->get();
    $this->authorize('show', [Project::class, Task::class]);
        

        return view('pages.tasks', compact('tasks', 'project'));
    }
    
    public function create($title)
    {
        

        $project = Project::where('title', $title)->first();
        $this->authorize('create', [Task::class, $project]);
       
        if (!$project) {
            abort(404); 
        }

        $members = $project->members;
   
       

    
        return view('pages.createTask', compact('project', 'members'));
    }

    public function store(Request $request, $title)
    {
        $project = Project::where('title', $title)->first();
      
        $validatedData = $request->validate([
            'title' => 'required|unique:task,title,NULL,id,id_project,' . $project->id,
            'content' => 'required',
            'priority' => 'required',
            'deadline' => 'date',
            'isCompleted' => 'boolean',
            'assigned' => 'required' 
        ]);

        // Criar uma nova tarefa associada ao projeto
        $task = new Task();
        $task->title = $request->input('title');
        $task->content = $request->input('content');
        $task->priority = $request->input('priority');
        $task->deadline = $request->input('deadline');

       
        $project = Project::where('title', $title)->first();
        $task->project()->associate($project);
        
        $user = $request->input('assigned');
        
        $this->authorize('store', [Task::class, $project]);
    
        $task->iscompleted=false;

        $currentDateTime=new DateTime();
        $task->datecreation = $currentDateTime->format('Y-m-d');
        
       
        $deadline = Carbon::parse($request->input('deadline'));
        if ($deadline->isPast()) {
            return redirect()->back()->withInput()->withErrors(['deadline' => 'The deadline must be after today']);
        }

        
        $task->save();
        $this->taskOwner($task->id, Auth::User()->id);
        $this->assignedTask($task->id, $user);

      
        

        
        return redirect()->route('task.show', ['title' => $title])->with('success', 'Tarefa criada com sucesso!');
        
    }

public function taskOwner($taskId,$userId){
    DB::table('taskowner')->insert([
        'id_user' => $userId,
        'id_task' => $taskId,
    ]);
}
public function assignedTask($taskId,$userId){
    DB::table('assigned')->insert([
        'id_user' => $userId,
        'id_task' => $taskId,
    ]);
}



public function isCompleted($title, $taskId)
{
    $project = Project::where('title', $title)->first();
    $task = Task::where('id', $taskId)->where('id_project', $project->id)->first();

    if ($task) {
        $task->iscompleted = !$task->iscompleted;
        $task->save();

        return Response::json(['iscompleted' => $task->iscompleted], 200);
    }

    return Response::json(['error' => 'Tarefa não encontrada.'], 404);
}


//falta editar task

public function edit($title,$titleTask)
{

    $project = Project::where('title', $title)->first();

    $task = Task::whereHas('project', function ($query) use ($title) {
        $query->where('title', $title);
    })
    ->where('title', $titleTask)
    ->first();
   

    return view('pages.editTask', compact('project','task'));
}



//falta meter botao para delete
public function delete($title,$titleTask){
    $project = Project::where('title', $title)->first();

    $task = Task::whereHas('project', function ($query) use ($title) {
        $query->where('title', $title);
    })
    ->where('title', $titleTask)
    ->first();
    

    $this->authorize('delete', [ $task, $project]);
    if($task){
   
    $task->delete();
    return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Tarefa apagada com sucesso!');
    }


}


public function update(Request $request, $title,$taskTitle)
{
   
    $project = Project::where('title', $title)->first();
    $task = Task::where('title', $taskTitle)->first();
   

    // Validação dos dados recebidos do formulário

      
      
        $validatedData = $request->validate([
            'title' => 'required|unique:task,title,' . $task->id . ',id,id_project,' . $project->id,
            'content' => 'required',
            'priority' => 'required',
            'deadline' => 'date',
            'isCompleted' => 'boolean',
            'assigned' => 'required' 
        ]);
        // Adicione outras regras de validação conforme necessário

        if ($task->title === $validatedData['title']) {
        }
        else {
            $task->title = $validatedData['title'];
        }

    // Atualize os campos da tarefa com base nos dados recebidos do formulário
    $task->content = $validatedData['content'];
    $task->priority = $validatedData['priority'];
    $task->deadline = $validatedData['deadline'];
    $currentDateTime=new DateTime();
    $task->datecreation = $currentDateTime->format('Y-m-d');
    
   
    $deadline = Carbon::parse($request->input('deadline'));
    if ($deadline->isPast()) {
        return redirect()->back()->withInput()->withErrors(['deadline' => 'The deadline must be after today']);
    }

    $task->save();

    // Redirecione para onde você precisa após a atualização
    return redirect()->route('task.show', ['title' => $task->project->title])->with('success', 'Tarefa atualizada com sucesso!');
}

public function search(Request $request, $title)
{
    $project = Project::where('title', $title)->first();
    $search = $request->input('search');

    if ($search) {
       
        $searchTerms = explode(' ', $search);
        $query = Task::where('id_project', $project->id);

        foreach ($searchTerms as $term) {
            $query->where(function ($q) use ($term) {
                $q->whereRaw("search @@ to_tsquery('english', ?)", [$term])
                    ->orWhere('title', 'ILIKE', '%' . $term . '%');
            });
        }

        $tasks = $query->get();
    
    
    } else {
        
    }

    return view('pages.user_search_task', ['task' => $tasks, 'search' => $search, 'project' => $project]);
}

}