<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{
   

    public function show($title)
    {
      
        $project = Project::where('title', $title)->first();

        if (!$project) {
            abort(404); 
        }

       
        $tasks = Task::where('id_project', $project->id)->get();

        return view('pages.tasks', compact('tasks', 'project'));
    }

    public function create($title)
    {
        
        $project = Project::where('title', $title)->first();

       
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
        
       

        
        $task->save();
        $this->taskOwner($task->id, Auth::User()->id);
        $this->assignedTask($task->id, $user);

       

        
        return redirect()->route('project.show', ['title' => $title])->with('success', 'Tarefa criada com sucesso!');
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

public function isCompleted($title,$taskId) {
  
    $project = Project::where('title', $title)->first();

    $tasks = Task::where('id', $taskId)->first();
    if($tasks){
        $tasks->iscompleted=true;
        $tasks->save(); 
    }

    return view('pages.tasks', compact('tasks', 'project'));
}
}