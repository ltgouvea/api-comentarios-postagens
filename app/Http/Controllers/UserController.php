<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\PasswordReset;

class UserController extends Controller
{
    public function requestPasswordReset(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return $this->sendError('User not found');
        }

        if ($user->generatePasswordResetMail()) {
            return $this->sendResponse([], 'Password reset requested sucessfully');
        } else {
            return $this->sendError('Something went wrong with the request, please try again.');
        }
    }

    public function changeUserPassword(Request $request)
    {
        $passwordResetRequest = PasswordReset::where('token', $request->input('token'))->first();

        if (!$passwordResetRequest) {
            return $this->sendError('Password reset request not found');
        }

        $messages = [
            'required' => 'The field :attribute is required.',
            'min' => 'The :attribute value must have at least :min characters.',
            'regex' => 'Your password must have at least one uppercase or lowercase letter, a number, and a special character.'
        ];

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*[\d\X])(?=.*[!@#$%&*().,;]).+$/', 'confirmed'],
        ], $messages);

        if ($validator->fails()) {
            return $this->sendError('Password change error', $validator->errors()->toArray(), 400);
        }

        if ($passwordResetRequest->redefineUserPassword($request->input('password'))) {
            return $this->sendResponse([], 'Password changed sucessfully');
        } else {
            return $this->sendError('Something went wrong with the password change, please try again.');
        }
    }
}
