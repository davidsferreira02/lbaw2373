<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Likes;
use App\Models\Project;
use Illuminate\Support\Facades\DB;


class LikeController extends Controller
{
    public function store(Request $request, $title, $titleTask, $commentId)
    {
        // Validação dos dados recebidos (comment_id e user_id)
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::where('title', $title)->first();
        $task = Task::where('title', $titleTask)->first();
         
        $this->authorize('store', [Likes::class, $project, $task]);

        // Verificar se o like já existe
        $existingLike = Likes::where('comment_id', $commentId)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingLike) {
            // Se o like já existe, remova-o
            $existingLike->delete();
        } else {
            // Se não existir, crie um novo like
            Likes::create([
                'comment_id' => $commentId,
                'user_id' => $request->user_id,
            ]);
        }

        // Obter a contagem atualizada de likes para o comentário específico
        $countLikes = $this->countLikes($commentId);

        return response()->json(['likesCount' => $countLikes]);
    }

    public function countLikes($commentId)
    {
        $count = DB::table('likes')->where('comment_id', $commentId)->count();
        return $count;
    }
}
