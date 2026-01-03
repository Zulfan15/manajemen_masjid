<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('articles')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        return Category::create($data);
    }

    public function show($id)
    {
        return Category::with('articles')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category;
    }

    public function destroy($id)
    {
        return Category::destroy($id);
    }
}
