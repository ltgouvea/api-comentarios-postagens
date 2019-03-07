<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, LaratrustUserTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_date',
        'last_login_ip',
        'last_password_change'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function passwordResets()
    {
        return $this->hasMany('App\PasswordReset', 'email', 'email');
    }

    public function saveLastLogin($ip = '')
    {
        $this->last_login_at = Carbon::now()->toDateTimeString();
        $this->last_login_ip = $ip;
        $this->save();
    }

    /**
     * Send the user a link to reset their password using the PasswordReset model
     *
     *
     * @return void
     */
    public function generatePasswordResetMail()
    {
        $passwordReset = new PasswordReset;
        return $passwordReset->createNewPasswordReset($this->email, $this->name);
    }

    /**
     * Changes the user password and save the date it was changed
     *
     * @param string $newPassword
     * @return void
     */
    public function redefinePassword(string $newPassword)
    {
        $this->password = Hash::make($newPassword);
        $this->last_password_change = Carbon::now();
        $this->save();
    }
}
