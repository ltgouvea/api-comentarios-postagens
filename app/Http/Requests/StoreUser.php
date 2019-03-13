<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\Request;

class StoreUser extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*[\d\X])(?=.*[!@#$%&*().,;]).+$/', 'confirmed'],
        ];
    }

    /**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
    public function messages()
    {
        return [
            'required' => 'The field :attribute is required.',
            'email' => 'Email is invalid.',
            'unique' => 'That :attribute is already in use.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'min' => 'The :attribute value must have at least :min characters.',
            'regex' => 'Your password must have at least one uppercase or lowercase letter, a number, and a special character.'
        ];
    }
}
