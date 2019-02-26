<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $createTokenRequest = Request::create(
            'oauth/token',
            'POST'
        );


        $tokenResponse = json_decode(Route::dispatch($createTokenRequest)->getContent());

        if (array_key_exists('error', $tokenResponse)) {
            return $this->sendError($tokenResponse->message, [], 401);
        }

        return $this->sendResponse($tokenResponse, 'Login success');
    }
}
