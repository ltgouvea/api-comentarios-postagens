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

    /**
     * BelongsTo App\User relationship
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    /**
     * Sends an email with a link to the user reset his password
     *
     * @param string $email
     * @param string $name
     * @return void
     */
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

    /**
     * Change the user password and send an email to notify it
     * Also deletes the current PasswordReset request
     *
     * @param string $newPassword
     * @return void
     */
    public function redefineUserPassword(string $newPassword)
    {
        $this->user->redefinePassword($newPassword);

        try {
            Mail::to($this->email)->send(new PasswordChangeMail($this->email, 'Your password has been changed'));
            $this->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
