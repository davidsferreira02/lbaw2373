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
       // $this->authorize('show', [Project::class, Task::class]);
        

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
      
        $validatedData = $request->validate([
            'title' => 'required|max:255',
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
        

    
        $task->iscompleted=false;

        $currentDateTime=new DateTime();
        $task->datecreation = $currentDateTime->format('Y-m-d');
        
       
        $deadline = Carbon::parse($request->input('deadline'));
        
        $task->save();
        $this->taskOwner($task->id, Auth::User()->id);
        $this->assignedTask($task->id, $user);

        if ($deadline->isPast()) {
            return redirect()->back()->withInput()->withErrors(['deadline' => 'The deadline must be after today']);
        }

        
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



public function delete($title,$idTask){
    $project = Project::where('title', $title)->first();
    $task = Task::where('id', $idTask)->where('id_project', $project->id)->first();
    $this->authorize('delete', $task);
    if($task){
   
    $task->delete();
    return redirect()->route('project.show', ['title' => $project->title])->with('success', 'Tarefa apagada com sucesso!');
    }


}


}