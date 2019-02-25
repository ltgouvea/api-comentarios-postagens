<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $createTokenRequest = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($createTokenRequest);
    }
}
