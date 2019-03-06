<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset as PasswordResetMail;
use Carbon\Carbon;


class PasswordReset extends Model
{
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
            Mail::to($this->email, $name)->send(new PasswordResetMail($this->email, $url, 'Password change'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
