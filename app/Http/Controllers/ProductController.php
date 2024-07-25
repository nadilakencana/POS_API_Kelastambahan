<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categorys;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{

    // api product to cusstomer
    public function getProduct_data(){
        try{
            $products = Products::all();
            return response()->json(['products' => $products], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch products','detail' => $e->getMessage()], 500);
        }
    }


    public function getProduct_Detail_customer($slug)
    {
        try {
            $product = Products::where('slug', $slug)->firstOrFail();
            return response()->json(['product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found', 'details' => $e->getMessage()], 404);
        }
    }
    public function getProduct_byCategory($slug)
    {
        try {
            $products_category = Products::whereHas('category', function ($query) use ($slug){
                $query->where('slug', $slug);
            })->get();
            return response()->json(['product' => $products_category], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found', 'details' => $e->getMessage()], 404);
        }
    }

    // api product to admin

    public function CreateDataProdut(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:10048',
            'id_category' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filePath = $file->store('images', 'public');
                $data['image'] = 'http://'.$request->getHttpHost().'/storage/' . $filePath;
            }

            $product = Products::create($data);

            return response()->json(['message' => 'Product created successfully','product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not created', 'details' => $e->getMessage()], 500);
        }
    }

    public function UpdateDataProdut(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:10048',
            'id_category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $product = Products::where('slug', $slug)->firstOrFail();
            if ($request->hasFile('image')) {
                if ($product->image) {
                    $oldImagePath = public_path(parse_url($product->image, PHP_URL_PATH));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('image');
                $filePath = $file->store('images', 'public');
                $data['image'] = 'http://'.$request->getHttpHost().'/storage/' . $filePath;
            }

            $product->update($data);
            return response()->json(['message' => 'Product updated successfully','product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not updated', 'details' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($slug)
    {
        try {
            $product = Products::where('slug', $slug)->firstOrFail();
            if ($product->image) {
                $imagePath = public_path(parse_url($product->image, PHP_URL_PATH));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not deleted', 'details' => $e->getMessage()], 500);
        }
    }
}
