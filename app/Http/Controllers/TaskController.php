<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{


    public function showCreateTaskForm($title){
        $project = Project::where('title', $title)->first();
        return view("pages.createTask", compact('project'));
    }
    public function create(Request $request,$title)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'priority' => 'required',
            'deadLine'=>'required',
        ]);
    
        $project = Project::where('title', $title)->firstOrFail();

        if(!$project){
            abort(404);
        }
        // Crie o projeto com os dados do formulário
        $task = new Task();
      
        $task->title = $request->input('title');
        $task->content = $request->input('description');
        $task->priority = $request->input('theme');
        $task->deadLine=$request->input('deadLine');
        $task->project()->attach($project);
        $task->owner()->attach($project->id,Auth::user()->id);  
             
        $task->save();
           
        
        return redirect()->route('project.show', ['title' => $title])->with('success', 'Tarefa criada com sucesso!');

    }
}