<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('kategori.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if($keyword === "" || $keyword == null){
            return redirect('/kategori');
        }

        $categories = Category::where('category_name', 'like', '%' . $keyword . '%')->get();

        return view('kategori.index', compact('categories', 'keyword'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        Category::create([
            'category_name' => $request->category_name,
        ]);

        return redirect('/kategori')->with('success', 'Kategori Berhasil Ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'category_name' => 'required',
        ]);

        Category::where('category_id', $request->id)->update([
            'category_name' => $request->category_name,
        ]);

        return redirect('/kategori')->with('success', 'Kategori Berhasil Diedit!');
    }

    public function destroy(string $id)
    {
        Category::where('category_id', $id)->delete();

        return redirect('/kategori')->with('success', 'Kategori Berhasil Dihapus!');
    }
}
