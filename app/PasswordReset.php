<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset as PasswordResetMail;
use App\Mail\PasswordChange as PasswordChangeMail;
use Carbon\Carbon;

class PasswordReset extends Model
{
    use SoftDeletes;

    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'email');
    }

    public function createNewPasswordReset(string $email, string $name)
    {
        $this->email = $email;
        $this->token = Hash::make($this->email . Carbon::now());
        $this->save();

        $url = url('new-password/' . $this->token);

        try {
            Mail::to($this->email, $name)->send(new PasswordResetMail($this->email, $url, 'Password change requested'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function redefineUserPassword($newPassword)
    {
        $this->user()->redefinePassword($newPassword);

        try {
            Mail::to($this->email)->send(new PasswordChangeMail($this->email, 'Your password has been changed'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
