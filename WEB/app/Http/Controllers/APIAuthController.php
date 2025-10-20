<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => ['Username atau Password salah!']], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['status' => 'success', 'message' => 'Login Berhasil!', 'access_token' => $token, 'user' => $user], 200);
    }

    public function register(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['status' => 'error', 'message' => $validation->errors()->all()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Register Berhasil!', 'user' => $user], 201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        DB::statement('DELETE FROM personal_access_tokens WHERE tokenable_id = ?', [$request->user()->user_id]);

        return response()->json(['status' => 'success', 'message' => 'Logout telah berhasil!'], 200);
    }
}
