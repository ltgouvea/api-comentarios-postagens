<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Comentario;
use App\Models\TransacaoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Notifications\ComentarioNaPostagem;

class ComentarioController extends Controller
{
    /**
     * Rota do endpoint para criação de comentários em postagens
     * -> controlado por throttle (o usuário pode comentar até 3x por minuto)
     * -> controlado por permissão (comentar-postagem)
     *
     * Regras:
     * - O usuário tem que ter saldo disponível caso queira comprar destaque
     * - O usuário ou o dono da postagem tem que ser um assinante, caso contrário
     * o comentário só é salvo com compra de destaque
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $input['login'] = $user->email;
        $input['assinante'] = $user->is_assinante;

        $usuarioTemSaldo = $user->verificarSaldo($input['quantidade_moedas']);
        $tentouComprarDestaque = $input['compra_destaque'] == true;

        $donoDaPostagem = \App\Models\Post::find($input['post_id'])->autor;
        $donoIsAssinante = $donoDaPostagem->is_assinante == true;
        $userIsAssinante = $user->is_assinante == true;

        $condicionalSemAssinantes = $donoIsAssinante == false && $userIsAssinante == false && $tentouComprarDestaque == false;

        if ($condicionalSemAssinantes) {
            return $this->sendError('O comentário não pode ser gravado pois o  dono da postagem e o usuário não são assinantes e o usuário não comprou destaque');
        }

        if ($tentouComprarDestaque && !$usuarioTemSaldo) {
            return $this->sendError('O usuário não tem saldo para realizar esta compra.');
        }

        if ($tentouComprarDestaque && $usuarioTemSaldo) {
            $this->comprarDestaque($user, $input);
        }

        $this->limparCacheDeComentariosDaPostagem($input['post_id']);
        $comentario = Comentario::create($input);

        $postagem = $comentario->post;

        $donoDaPostagem = $postagem->autor;
        $donoDaPostagem->notify(new ComentarioNaPostagem($postagem, $user));

        return $this->sendResponse($comentario, 'Comentário criado com sucesso.');
    }

    /**
     * Tenta comprar X moedas (1 moeda seria 1 real, pra facilitar)
     * antes de salvar comentários com a flag compra_destaque no input
     *
     * @return void || Error
     */
    public function comprarDestaque($user, $comentario)
    {
        $quantidadeDeMoedas = $comentario['quantidade_moedas'];

        $user->debitar($quantidadeDeMoedas);

        TransacaoLog::create([
            'user_id' => $user->id,
            'creditos' => 0,
            'debitos' => $quantidadeDeMoedas,
            'retencao_interna' => false,
        ]);

        $this->gerarRetencaoDoSistema($quantidadeDeMoedas);
    }

    /**
     * Método responsável por criar a transação de retenção interna
     * de comentários comprando destaque para o sistema
     *
     * @return void
     */
    public function gerarRetencaoDoSistema($quantidadeDeMoedas)
    {
        $usuarioSistema = User::whereEmail('sistema')->first();

        $porcentagemDeRetencaoParaComentarios = 0.05;
        $valorRetido = $quantidadeDeMoedas * $porcentagemDeRetencaoParaComentarios;

        TransacaoLog::create([
            'user_id' => $usuarioSistema->id,
            'creditos' => $valorRetido,
            'debitos' => 0,
            'retencao_interna' => true,
        ]);

        $usuarioSistema->creditar($valorRetido);
    }

    /**
     * Antes de salvar um comentário novo é necessário limpar o cache para que a listagem seja atualizada
     *
     * @return void
     */
    public function limparCacheDeComentariosDaPostagem($id)
    {
        if (Cache::has("postagem_$id")) {
            Cache::forget("postagem_$id");
        }
    }

    /**
     * Rota para exclusão de comentários
     * Regra: só o dono do comentário ou da postagem relacionada podem excluir o comentário
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $comentario = Comentario::find($id);

        if (!$comentario) {
            return $this->sendError('Comentário não encontrado');
        }

        $isDonoDoComentario = $comentario->user_id == Auth::id();
        $isDonoDaPostagem = $comentario->post->user_id == Auth::id();
        $naoPodeExcluir = $isDonoDoComentario == false && $isDonoDaPostagem == false;

        if ($naoPodeExcluir) {
            return $this->sendError('Você não tem permissão para executar essa ação');
        }

        $comentario->delete();

        return $this->sendResponse([], 'Comentário excluído com sucesso');
    }
}
