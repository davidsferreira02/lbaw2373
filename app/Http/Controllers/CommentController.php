<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Comment;
use App\Models\User;
use App\Events\CommentLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CommentController extends Controller
{
    

   
    public function show($title,$taskId){
        
        $project = Project::where('title', $title)->first();
     
        $task=Task::find($taskId);
        if($task && $project){
     $this->authorize('show', [Comment::class, $project, $task]);
        $comments = $task->comments()->get();
        return view('pages.comment', compact('task','project','comments'));
        }
        return redirect()->route('task.show', ['title' => $project->title]);

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
    
        
        $comment->date = $formattedDateTime;


        $user = User::where('username', Auth::user()->username)->first();
        $comment->save();
        $this->addCommentOwner($user->username,$comment->id);

        
        return redirect()->back();
    }


    public function edit($title,$titleTask,$commentid){

        $task = Task::where('title', $titleTask)->first();
        $comment=Comment::find($commentid);
        $project = Project::where('title', $title)->first();
        
        if($task && $comment && $project){

        $this->authorize('edit', [Comment::class, $project, $task,$comment]);
     
        return view('pages.editComment',['project'=>$project,'task'=>$task,'comment'=>$comment]);
    }
    return redirect()->route('task.show', ['title' => $project->title]);
        
        
    }
    public function commentUpdate($title,$titleTask,$commentid,Request $request){

        $project = Project::where('title', $title)->first();


        $task = Task::where('title', $titleTask)->first();
        $comment = Comment::where('id', $commentid)
        ->where('id_task', $task->id) // Supondo que você tenha $taskid para a tarefa desejada
        ->firstOrFail();

        $this->authorize('edit', [Comment::class, $project, $task,$comment]);


        $task = Task::where('id', $task->id)
        ->where('id_project', $project->id) // Supondo que você tenha $taskid para a tarefa desejada
        ->firstOrFail();



     
        
        $validatedData = $request->validate([
            
            'content'=> 'required|string|max:255'
            
        ]);

        $comment->content = $request->input('content');
        $currentDateTime = Carbon::now();

    
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');


        $formattedDateTime = substr($formattedDateTime, 0, -3);
        $comment->owner()->detach();
        $comment->owner()->attach(Auth::user());
    
        
        $comment->date = $formattedDateTime;
        $comment->save();

      //  dd($project->title,$task->title,$comment->content,$comment->date);
    
       

        return redirect()->route('task.comment',['title' => $project->title, 'taskId' => $task->id])
        ->withErrors($validatedData)
        ->withInput();
}

    public function delete($title,$titleTask,$idComment){
        $project = Project::where('title', $title)->first();
        $task = Task::where('title', $titleTask)->where('id_project', $project->id)->first();
        $comment=Comment::findorfail($idComment);
        $this->authorize('delete', [Comment::class, $project, $task,$comment]);

        if($comment){
            $comment->delete();
        }

        return redirect()->back();
    }

    public function addCommentOwner($username,$idcomment){
        $user = User::where('username', $username)->first();
        $comment = Comment::findOrFail($idcomment);
        DB::table('commentowner')->insert([
            'id_comment' => $comment->id,
            'id_user' => $user->id,
        ]);
       



    }



}
