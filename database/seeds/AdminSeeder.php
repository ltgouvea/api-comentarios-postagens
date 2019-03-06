<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!App\User::where('email', 'admin@admin.com')->first()) {
            $this->command->info('Creating admin user...');
            App\User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123456')
            ]);
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already created.');
        }
    }
}
