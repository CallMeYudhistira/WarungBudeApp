<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::simplePaginate(15);

        return view('users.index', compact('users'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/users');
        }

        $users = User::where('name', 'like', '%' . $keyword . '%')->simplePaginate(15);

        return view('users.index', compact('users', 'keyword'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required',
            'password' => 'required|min:4',
            'role' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();
        if ($user || $user != null) {
            $validator->errors()->add('username', 'username has been already taken!.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User Berhasil Ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required',
            'password' => 'required|min:4',
            'role' => 'required',
        ]);

        if ($request->password == '- - -') {
            User::where('user_id', $request->id)->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'username' => $request->username,
                'role' => $request->role,
            ]);
        } else {
            User::where('user_id', $request->id)->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        }

        return redirect('/users')->with('success', 'User Berhasil Diedit!');
    }

    public function destroy(string $id)
    {
        User::where('user_id', $id)->delete();

        return redirect('/users')->with('success', 'User Berhasil Dihapus!');
    }
}
