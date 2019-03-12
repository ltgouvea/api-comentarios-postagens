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

    /**
     * Array containing validation rules of this model
     *
     * @var array
     */
    protected $validation_rules = [
        'rules' => [
            'name' => ['required' => 'required', 'string', 'max:255'],
            'email' => ['required' => 'required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required' => 'required', 'string', 'min:6', 'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*[\d\X])(?=.*[!@#$%&*().,;]).+$/', 'confirmed'],
        ],
        'messages' => [
            'required' => 'The field :attribute is required.',
            'email' => 'Email is invalid.',
            'unique' => 'That :attribute is already in use.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'min' => 'The :attribute value must have at least :min characters.',
            'regex' => 'Your password must have at least one uppercase or lowercase letter, a number, and a special character.'
        ]
    ];

    /**
     * HasMany App\passwordResets relationship
     *
     * @return HasMany
     */
    public function passwordResets()
    {
        return $this->hasMany('App\PasswordReset', 'email', 'email');
    }

    /**
     * Hash password using Illuminate\Support\Facades\Hash
     *
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password)
    {
        return Hash::make($password);
    }

    /**
     * Get the validation rules that apply to the STORE request
     *
     * @return object
     */
    public function getStoreValidationRules()
    {
        return (object)$this->validation_rules;
    }

    /**
     * Get the validation rules that apply to the STORE request
     *
     * @return object
     */
    public function getUpdateValidationRules()
    {
        $updateValidationRules = (object)$this->validation_rules;

        // Unset the 'required' rule
        foreach ($updateValidationRules->rules as $key => $value) {
            unset($updateValidationRules->rules[$key]['required']);
        }

        return $updateValidationRules;
    }

    /**
     * Get the password validation rule
     *
     * @return object
     */
    public function getPasswordValidationRules()
    {
        return (object)[
            'rules' => [$this->validation_rules['rules']['password']],
            'messages' => $this->validation_rules['messages']
        ];
    }

    /**
     * Save last login time and IP
     *
     * @param string $ip
     * @return void
     */
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
