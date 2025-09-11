<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if($keyword === "" || $keyword == null){
            return redirect('/users');
        }

        $users = User::where('name', 'like', '%' . $keyword . '%')->get();

        return view('users.index', compact('users', 'keyword'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User Berhasil Ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = User::where('user_id', $id)->first();

        return view('users.update', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        User::where('user_id', $request->id)->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User Berhasil Diedit!');
    }

    public function destroy(string $id)
    {
        User::where('user_id', $id)->delete();

        return redirect('/users')->with('success', 'User Berhasil Dihapus!');
    }
}
