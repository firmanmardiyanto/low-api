<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
        $this->middleware('isAdmin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
            'status' => 'success',
            'category' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $category = \App\Models\Category::find($id);
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
