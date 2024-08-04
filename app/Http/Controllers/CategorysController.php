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
            return response()->json([
                'message' => 'Category fetched successfully',
                'data' => $category
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Failed to fetch category',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    //api category to admin


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
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category','data' => $e->getMessage()], 500);
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
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category','data' => $e->getMessage()], 500);
        }
    }

    public function DeleteDataCategory($slug){
        try {
            $category = Categorys::where('slug', $slug)->delete();
            return response()->json([
                'message' => 'Category deleted successfully',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category','data' => $e->getMessage()], 500);
        }
    }
}
