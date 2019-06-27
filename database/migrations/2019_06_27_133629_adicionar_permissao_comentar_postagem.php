<?php

use Illuminate\Database\Migrations\Migration;
use App\Permission;

class AdicionarPermissaoComentarPostagem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create([
            'name' => 'comentar-postagem',
            'display_name' => 'Comentar Postagens',
            'description' => 'Permite que o usuário realize comentários em postagens.',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereName('comentar-postagem')->delete();
    }
}
