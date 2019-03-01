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
            return $this->sendError('User not found');
        }

        if ($user->generatePassword()) {
            return $this->sendResponse([], 'Password reset requested sucessfully');
        } else {
            return $this->sendError('Something went wrong with the request, please try again.');
        }
    }
}
