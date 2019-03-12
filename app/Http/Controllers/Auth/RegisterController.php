<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $user = new User;
        $validator = Validator::make($request->all(), $user->getStoreValidationRules()->rules, $user->getStoreValidationRules()->messages);

        if ($validator->fails()) {
            return $this->sendError('Creation error', $validator->errors()->toArray(), 400);
        }

        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->save();

        return $this->sendResponse($user, 'User created');
    }
}
