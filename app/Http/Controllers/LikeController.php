<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Likes;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function store(Request $request,$title,$taskID)
    {
        // Validação dos dados recebidos (comment_id e user_id)
        $request->validate([
            'comment_id' => 'required|exists:comment,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Criando um novo like
        $like = Likes::create([
            'comment_id' => $request->comment_id,
            'user_id' => $request->user_id,
        ]);
        $project = Project::where('title', $title)->first();


        $like->save();
        
        $task = Task::where('id', $taskID)->where('id_project', $project->id)->first();


        // Retornar uma resposta ou redirecionar conforme necessário
        // Exemplo de retorno
       
        
        return redirect()->route('task.comment', ['title' => $project->title, 'taskId' => $task->id])->with('success', 'Like com sucesso');

    }

    public function destroy($id)
    {
        // Encontrar e deletar o like pelo ID
        $like = Likes::find($id);

        if (!$like) {
            return response()->json(['message' => 'Like não encontrado'], 404);
        }

        $like->delete();

        // Retornar uma resposta ou redirecionar conforme necessário
        // Exemplo de retorno
        return response()->json(['message' => 'Like removido com sucesso']);
    }

    public function countLikes($commentId){
        $count = DB::table('like')->where('comment_id', $commentId)->count();
        return $count;

    }
}


