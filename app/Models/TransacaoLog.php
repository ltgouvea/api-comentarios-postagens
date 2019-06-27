<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransacaoLog extends Model
{
    protected $fillable = [
        'user_id',
        'creditos',
        'debitos',
        'retencao_interna',
    ];

    /**
     * Relação com o usuario
     *
     * @return Relationship (belongsTo)
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
