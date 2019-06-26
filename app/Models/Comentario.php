<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'login',
        'assinante',
        'compra_destaque',
        'quantidade_moedas',
        'comentario',
    ];

    protected $appends = [
        'em_destaque'
    ];

    /**
     * Relacionmento com a postagem
     *
     * @return Relationship (belongsTo)
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }

    /**
     * Relacionmento com o usuário
     *
     * @return void
     */
    public function autor()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Retorna true caso a diferença em minutos entre a data de criação do comentário e o número de moedas
     * usado na compra do destaque seja menor ou igual ao número de moedas
     */
     public function getEmDestaqueAttribute()
     {
         $isDestaque = $this->compra_destaque == true;

         if (!$isDestaque) {
             return false;
         }

         $moedasUtilizadas = $this->quantidade_moedas;
         $dataDeCriacao = $this->created_at;
         $agora = \Carbon\Carbon::now();

         $diferencaEmMinutos = $agora->diffInMinutes($dataDeCriacao);

         return $diferencaEmMinutos <= $moedasUtilizadas;
     }

    /**
     * Scope para filtrar por destaques
     */
     public function scopeDestaques($query)
     {
        return $query->where('compra_destaque', true);
     }
}
