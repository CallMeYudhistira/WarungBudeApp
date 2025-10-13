<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('index');
    }

    public function login(){
        return view('login');
    }

    public function loginProcess(Request $request){
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
            return redirect('/login')->with('error', 'Username atau Password salah!');
        }
    }

    public function register(){
        return view('register');
    }

    public function registerProcess(Request $request){
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'kasir',
        ]);

        return redirect('/login')->with('success', 'Registrasi Berhasil!\nAnda Bisa Login Sekarang!');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/');
    }
}
