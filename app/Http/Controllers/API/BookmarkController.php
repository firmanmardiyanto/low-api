<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required|integer|exists:articles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $article = Article::find($request->article_id);
        if ($article->user_id == auth()->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot bookmark your own article',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = auth()->user();
        $user->bookmarks()->attach($request->article_id);
        return response()->json([
            'status' => 'success',
            'message' => 'Article bookmarked',
            'article' => $article,
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
