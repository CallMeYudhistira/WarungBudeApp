<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class APIAuthController extends Controller
{
    public function login(Request $request){
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['status' => 'error', 'message' => $validation->errors()->all()], 400);
        }

        $user = User::where('username', $request->username)->first();

        if($user && $user->password === $request->password){
            Auth::login($user);
            return response()->json(['status' => 'success', 'message' => 'Login Berhasil!', 'data' => $user], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Username atau Password salah!'], 401);
        }
    }

    public function guest(){
        $user = User::where('username', 'guest')->first();

        if($user){
            Auth::login($user);
            return response()->json(['status' => 'success', 'message' => 'You just used guest account!', 'data' => $user], 200);
        }
    }

    public function logout(){
        Auth::logout();

        return response()->json(['status' => 'success', 'message' => 'Logout telah berhasil!'], 302);
    }
}
