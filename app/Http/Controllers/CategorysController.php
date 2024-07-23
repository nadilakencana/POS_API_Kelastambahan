<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorys;
use Illuminate\Support\Facades\Validator;

class CategorysController extends Controller
{
    // api category to custommer
    public function getCategory()
    {
       try{
            $category = Categorys::all();
            return response()->json(['category' => $category], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch category','detail' => $e->getMessage()], 500);
        }
    }

    //api category to admin
    public function getCategory_admin()
    {
       try{
            $category = Categorys::all();
            return response()->json(['category' => $category], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch category','detail' => $e->getMessage()], 500);
        }
    }

    public function CreateDataCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $category = Categorys::create($data);
            return response()->json(['message' => 'Category created successfully','category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category','detail' => $e->getMessage()], 500);
        }
    }

    public function UpdateDataCategory(Request $request, $slug){

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $category = Categorys::where('slug', $slug)->update($data);
            return response()->json(['message' => 'Category updated successfully','category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category','detail' => $e->getMessage()], 500);
        }
    }

    public function DeleteDataCategory($slug){
        try {
            $category = Categorys::where('slug', $slug)->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category','detail' => $e->getMessage()], 500);
        }
    }
}
