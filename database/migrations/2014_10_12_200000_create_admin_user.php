<?php

use Illuminate\Database\Migrations\Migration;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!App\User::where('email', 'admin')->first()) {
            App\User::create([
                'name' => 'Admin',
                'email' => 'admin',
                'password' => Hash::make('123456')
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
        App\User::where('email', 'admin')->first()->delete();
    }
}
