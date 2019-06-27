<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionarUsuarioSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!App\User::where('email', 'sistema')->first()) {
            App\User::create([
                'name' => 'Sistema',
                'email' => 'sistema',
                'password' => Hash::make('An912_*376¨.las*&!0nan #99s::verystrongpasswordindeed'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
