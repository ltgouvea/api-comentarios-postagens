<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function requestPasswordReset(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $this->sendError('User not found');
        }

        $user->generatePassword();

        $this->sendResponse([], 'Password reset requested sucessfully');
    }
}
