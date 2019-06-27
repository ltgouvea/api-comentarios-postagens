<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $post = Post::create($input);

        return $this->sendResponse($post, 'Postagem criada com sucesso.');
    }

    /**
     * Lista os comentários de uma postagem
     * -> controlado por throttle (o usuário pode consultar esse endpoint até 20x por minuto)
     *
     * @return Response
     */
    public function listarComentariosDaPostagem($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Postagem não encontrada.');
        }

        // Guarda valores em cache para otimizar desempenho do endpoint
        $comentariosDaPostagem = Cache::remember("postagem_$id", 60, function () use ($post){
            // Ordena cronologicamente, com os destaques ordenados por quantidade de moedas
            return $post->comentarios()->orderBy('created_at', 'desc')->orderBy('compra_destaque', 'desc')->orderBy('quantidade_moedas', 'desc')->paginate(20);
        });

        return $this->sendResponse($comentariosDaPostagem, 'Comentários carregados com sucesso');
    }
}
