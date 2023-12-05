<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Comment;
use Illuminate\Http\Request;
use Carbon\Carbon;
class CommentController extends Controller
{
    
    public function show($title,$taskId){
        
        $project = Project::where('title', $title)->first();
     
        $task=Task::find($taskId);
     $this->authorize('create', [Comment::class, $project, $task]);
        $comments = $task->comments()->get();
        return view('pages.comment', compact('task','project','comments'));

    }

    public function store(Request $request,$title,$taskId){

        $project = Project::where('title', $title)->first();
       
        $comment = new Comment();
        $comment->content = $request->input('content');
        $task=Task::find($taskId);
        $this->authorize('create', [Comment::class, $project, $task]);
        $comment->id_task=$task->id;
        $currentDateTime = Carbon::now();

    
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

        // Se quiseres remover a fração de milissegundos (até os segundos)
        $formattedDateTime = substr($formattedDateTime, 0, -3);
    
        // Salvar o comentário com a data formatada
        $comment->date = $formattedDateTime;
    
        $comment->save();

        
        return redirect()->back();
    }

    public function edit(){

    }

    public function delete(){

    }



}
