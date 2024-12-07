<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    //For searching 
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json($products);
    }

    //Show all the products
    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|string|max:255',
            'quantity'    => 'required|integer',
            'price'       => 'required|numeric',
            'date_time'   => 'required|date',
            'category'    => 'nullable|string',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product($request->only(
            'name', 'description', 'type', 'quantity', 'price', 'date_time', 'category'
        ));

        $uploadPath = public_path('/upload/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $name = time().rand(1000,9999).'.'.$imageFile->getClientOriginalExtension();
                $imageFile->move($uploadPath, $name);
                $images[] = $name;
                Log::info('Image saved successfully: ' . $name);
            }
        } else {
            Log::info('No image file found in the request.');
        }

        $product->images = $images; 
        $product->save();
        return response()->json(['success' => 'Product created successfully']);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|string|max:255',
            'quantity'    => 'required|integer',
            'price'       => 'required|numeric',
            'date_time'   => 'required|date',
            'category'    => 'nullable|string',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product->fill($request->only(
            'name', 'description', 'type', 'quantity', 'price', 'date_time', 'category'
        ));

        $uploadPath = public_path('/upload/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $oldImages = $product->images ?? [];
        $newImages = [];

        if ($request->hasFile('images')) {
        
            foreach ($oldImages as $oldImage) {
                if (file_exists($uploadPath.$oldImage)) {
                    unlink($uploadPath.$oldImage);
                }
            }

         
            foreach ($request->file('images') as $imageFile) {
                $name = time().rand(1000,9999).'.'.$imageFile->getClientOriginalExtension();
                $imageFile->move($uploadPath, $name);
                $newImages[] = $name;
                Log::info('Image saved successfully: ' . $name);
            }
        } else {
         
            $newImages = $oldImages;
        }

        $product->images = $newImages;
        $product->save();
        return response()->json(['success' => 'Product updated successfully']);
    }

    public function destroy(Product $product)
    {
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $img) {
                if (file_exists(public_path('/upload/'.$img))) {
                    unlink(public_path('/upload/'.$img));
                }
            }
        }
        $product->delete();
        return response()->json(['success' => 'Product deleted successfully']);
    }
}
