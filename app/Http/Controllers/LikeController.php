<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Likes;


class LikeController extends Controller
{
    public function store(Request $request)
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

        $like->save();

        // Retornar uma resposta ou redirecionar conforme necessário
        // Exemplo de retorno
        return response()->json(['message' => 'Like criado com sucesso', 'like' => $like]);
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
}
