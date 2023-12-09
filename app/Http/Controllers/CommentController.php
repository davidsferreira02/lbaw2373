<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CommentController extends Controller
{
    
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
    
        // Salvar o comentário com a data formatada
        $comment->date = $formattedDateTime;


        $user = User::where('username', Auth::user()->username)->first();
        $comment->save();
        $this->addCommentOwner($user->username,$comment->id);

        
        return redirect()->back();
    }

    public function edit(){

    }

    public function delete(){

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
