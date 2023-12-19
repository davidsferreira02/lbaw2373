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
    

    function like(Request $request) { //notificaçoes 
        event(new CommentLike($request->id));
    }
    public function show($title,$taskId){
        
        $project = Project::where('title', $title)->first();
     
        $task=Task::find($taskId);
     $this->authorize('show', [Comment::class, $project, $task]);
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
    
        
        $comment->date = $formattedDateTime;


        $user = User::where('username', Auth::user()->username)->first();
        $comment->save();
        $this->addCommentOwner($user->username,$comment->id);

        
        return redirect()->back();
    }

    public function commentUpdate($title,$taskId,$commentid,Request $request){

        $project = Project::where('title', $title)->first();

        $task=Task::findOrFail($taskId);


        $task = Task::where('id', $taskId)
        ->where('id_project', $project->id) // Supondo que você tenha $taskid para a tarefa desejada
        ->firstOrFail();


        $comment = Comment::where('id', $commentid)
        ->where('id_task', $taskId) // Supondo que você tenha $taskid para a tarefa desejada
        ->firstOrFail();

     
        
        $validatedData = $request->validate([
            
            'content'=> 'required|string|max:255'
            
        ]);

        $comment->content = $request->input('content');
        $currentDateTime = Carbon::now();

    
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');


        $formattedDateTime = substr($formattedDateTime, 0, -3);
    
        
        $comment->date = $formattedDateTime;
    
       
        return redirect()->route('task.comment', ['title' => $project->title, 'taskId' => $task->id])->with('success', 'Comentário atualizado com sucesso!');


    }

    public function delete($title,$titleTask,$idComment){
        $project = Project::where('title', $title)->first();
        $task = Task::where('title', $titleTask)->where('id_project', $project->id)->first();
        $comment=Comment::findorfail($idComment);

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
