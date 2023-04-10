<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::all();
    }

    public function create(Request $request): bool
    {
        $product = Product::create([

            'name'  => $request->input("name"),

            'price' => $request->input("price"),

        ]);

        return true;
    }

    public function update(ProductUpdateRequest $request,Product $product): bool
    {
        $product->update($request->validated());

        return true;
    }

    public function delete(Product $product)
    {
        $product->delete();

        return true;
    }
}
