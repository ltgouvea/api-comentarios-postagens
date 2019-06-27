<?php

use Illuminate\Database\Migrations\Migration;
use App\Role;

class AdicionarRolesBasicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usuario = new Role();
        $usuario->name         = 'usuario';
        $usuario->display_name = 'Usuários';
        $usuario->description  = 'Usuário comum';
        $usuario->save();

        $admin = new Role();
        $admin->name         = 'administrador';
        $admin->display_name = 'Admin do sistema';
        $admin->description  = 'Usuário com set full plate +10 encantado com todas as permissões';
        $admin->save();

        $usuario->attachPermission('comentar-postagem');
        $admin->attachPermission('comentar-postagem');

        $usuarios = \App\User::get()->all();

        foreach ($usuarios as $usuario) {
            $usuario->attachRole('usuario');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::whereIn('name', ['usuario', 'administrador'])->delete();
    }
}
