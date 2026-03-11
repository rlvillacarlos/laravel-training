<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginAuthRequest;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function show() {
        return view('auth.show');
    }

    public function login(LoginAuthRequest $request) {
        $credentials = $request->safe(['name', 'password']);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect()->route('games.index');
        }

        return back()->withErrors([
            'name' => 'The email and password does not match.',
        ])->onlyInput('name');
    }

    public function logout(Request $request) {
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('login');
    }
}
