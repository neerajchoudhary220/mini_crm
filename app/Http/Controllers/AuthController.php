<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view("auth.login");
    }

    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->only('email', 'password');
        if (! Auth::attempt($credentials, $loginRequest->get('remember'))) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records. '])->withInput();
        }
        $loginRequest->session()->regenerate();
        return redirect()->route('dashboard');
    }
}
