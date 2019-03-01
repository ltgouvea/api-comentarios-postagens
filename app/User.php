<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\PasswordReset;
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

    public function saveLastLogin($ip = '')
    {
        $this->last_login_at = Carbon::now()->toDateTimeString();
        $this->last_login_ip = $ip;
        $this->save();
    }

    /**
     * Enviar Senha de Acesso ao Usuário.
     *
     */
    public function generatePassword()
    {

        $passwordHash = Hash::make($this->id);
        $url = url('new-password/' . $passwordHash);
        $mailSubject = $this->last_password_change == null ? 'Password change' : 'Password creation';

        try {
            Mail::to($this->email, $this->name)->send(new PasswordReset($this->email, $url, $mailSubject));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
