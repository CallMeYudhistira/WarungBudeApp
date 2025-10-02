<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if($user && $user->password === $request->password){
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/home');
        } else {
            return redirect('/')->with('error', 'Username atau Password salah!');
        }
    }

    public function guest(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if($user && $user->password === $request->password){
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/home');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/login');
    }
}
