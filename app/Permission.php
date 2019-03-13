<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'lt_permission_role', 'lt_permission_id', 'lt_role_id');
    }
}
