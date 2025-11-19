<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        // send back if the credentials are wrong and redirect if they are correct
        if (auth()->attempt($credentials)) {
            return redirect()->intended('admin-panel');
        } else {
            return redirect()->route('login')->with('error', 'Email atau Password Salah');
        }
        return redirect()->route('login');
    }

    public function logoutAction()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
