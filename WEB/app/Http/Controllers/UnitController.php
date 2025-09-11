<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();

        return view('satuan.index', compact('units'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if($keyword === "" || $keyword == null){
            return redirect('/satuan');
        }

        $units = Unit::where('unit_name', 'like', '%' . $keyword . '%')->get();

        return view('satuan.index', compact('units', 'keyword'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required',
        ]);

        Unit::create([
            'unit_name' => $request->unit_name,
        ]);

        return redirect('/satuan')->with('success', 'Satuan Berhasil Ditambahkan!');
    }

    public function edit(string $id)
    {
        $unit = Unit::where('unit_id', $id)->first();

        return view('satuan.update', compact('unit'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'unit_name' => 'required',
        ]);

        Unit::where('unit_id', $request->id)->update([
            'unit_name' => $request->unit_name,
        ]);

        return redirect('/satuan')->with('success', 'Satuan Berhasil Diedit!');
    }

    public function destroy(string $id)
    {
        Unit::where('unit_id', $id)->delete();

        return redirect('/satuan')->with('success', 'Satuan Berhasil Dihapus!');
    }
}
