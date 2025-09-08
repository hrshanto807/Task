<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->latest()->paginate(5);
        $categories = Category::where('status', 1)->get();
        return view('products.index', compact('products', 'categories'));

    }

    public function create()
    {
       //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($request->only('name', 'description', 'price'));
        $product->categories()->attach($request->categories);

        return redirect()->route('products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
       //
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only('name', 'description', 'price'));
        $product->categories()->sync($request->categories);

        return redirect()->route('products.index')->with('success', 'Updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Deleted!');
    }
}
