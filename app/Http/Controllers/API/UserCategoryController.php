<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function sync(Request $request)
    {
        $user = auth()->user();
        $user->categories()->sync($request->categories);
        return response()->json([
            'status' => 'success',
        ]);
    }
}
