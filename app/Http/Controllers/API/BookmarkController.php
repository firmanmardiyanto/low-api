<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $user->bookmarks()->attach($request->article_id);
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function destroy($article_id)
    {
        $user = auth()->user();
        $user->bookmarks()->detach($article_id);
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $bookmarks = $user->bookmarks;
        return response()->json([
            'status' => 'success',
            'bookmarks' => $bookmarks,
        ]);
    }
}
