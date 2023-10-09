<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        return new ProductCollection($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|image',
            'description' => 'required|max:65535',
        ]);

        $imagePath = $request->file('image')->store('images/products');

        $product = new Product();
        $product->user_id = auth()->id();
        $product->name = $request->input('name');
        $product->image = $imagePath;
        $product->description = $request->input('description');
        $product->user()->associate(auth()->user());
        $product->save();

        return response()->json(['message' => 'Product added successfully'], 201);
    }

    public function updateProduct2(Request $request, Product $product)
    {

        if (Gate::denies('update', $product)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $imagePath = $request->file('image')->store('images/products');

        $product->name = $request->input('name');
        $product->image = $imagePath;
        $product->description = $request->input('description');

        $product->save();

        return response()->json(['message' => 'Product updated successfully']);
    }
    public function removeImage($file_name)
    {
        if(Storage::exists($file_name)){
            Storage::delete($file_name);
            return true;
        } else {
            return false;
        }
    }
    public function updateProduct(Request $request, Product $product)
    {

        if (Gate::denies('update', $product)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'max:255',
            'image' => 'image|max:2048',
            'description' => 'max:65200',
        ]);

        $imagePath =  $request->file('image')->store('images/products');

        if($request->input('image') !== "") {
            $this->removeImage($product->image);
        }

        $product->name = $request->input('image') ?? $product->description;
        $product->image = $request->input('image') ?? $product->image;
        $product->image = $imagePath ?? $product->image;

        $product->description = $request->input('description') ?? $product->description;

        $product->save();

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy(Product $product)
    {
        if (Gate::denies('delete', $product)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function assignProductToUser(Request $request, Product $product, User $user)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $user = User::findOrFail($request->input('user_id'));

        if (Gate::denies('assign', $product)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $product->user()->associate($user);
        $product->save();

        return response()->json(['message' => 'Product assigned to user successfully']);
    }
}
