<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserCRUDPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            [
                'name' => 'read-user',
                'display_name' => 'Read User',
                'description' => 'Read User',
            ],
            [
                'name' => 'find-user',
                'display_name' => 'Find User',
                'description' => 'Find User',
            ],
            [
                'name' => 'create-user',
                'display_name' => 'Create User',
                'description' => 'Create User',
            ],
            [
                'name' => 'update-user',
                'display_name' => 'Update User',
                'description' => 'Update User',
            ],
            [
                'name' => 'delete-user',
                'display_name' => 'Delete User',
                'description' => 'Delete User',
            ],
        ];

        $admin = App\User::where('email', 'admin')->first();

        foreach ($permissions as $permission) {
            $createdPermission = App\Permission::create($permission);
            $admin->attachPermission($createdPermission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\Permission::whereIn('name', [
            'read-user',
            'find-user',
            'create-user',
            'update-user',
            'delete-user',
        ])->delete();
    }
}
