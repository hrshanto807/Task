<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Categories';
        $categories = Category::latest()->paginate(5);
        return view('categories.index', compact('categories','title'));
    }

    public function create()
    {
       //
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
       //
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Updated!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Deleted!');
    }

     public function deactivate(Category $category)
    {
        $category->status = 0;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category deactivated.');
    }

    public function activate(Category $category)
    {
        $category->status = 1;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category activated.');
    }
}

