<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'tipo',
        'source',
        'assinante',
        'user_id',
    ];

    /**
     * Relacionamento com o dono da postagem
     *
     * @return Relationship (belongsTo)
     */
    public function autor()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Relacionamento com comentários
     *
     * @return Relationship (hasMany)
     */
    public function comentarios()
    {
        return $this->hasMany('App\Models\Comentario');
    }
}
