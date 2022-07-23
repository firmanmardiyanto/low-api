<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $publishedArticles = Article::where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $publishedArticles,
        ]);
    }

    public function show($id)
    {
        $article = Article::find($id);
        return response()->json([
            'status' => 'success',
            'article' => $article,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles',
            'description' => 'required|string|max:255',
            'picture' => 'required|string|max:255',
            'published' => 'required|boolean',
            'views' => 'required|integer',
            'article_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $article = new Article;
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->description = $request->description;
        $article->content = $request->content;
        $article->published = $request->published;
        $article->tags = $request->tags;
        $article->user_id = auth()->user()->id;
        $article->categories()->sync($request->categories);
        $article->save();
        return response()->json([
            'status' => 'success',
            'article' => $article,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $id,
            'description' => 'required|string|max:255',
            'picture' => 'required|string|max:255',
            'published' => 'required|boolean',
            'views' => 'required|integer',
            'article_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        if (!$article = Article::where('id', $id)->where('user_id', auth()->user()->id)->first()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Article not found',
            ]);
        }

        $article = Article::find($id);
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->description = $request->description;
        $article->content = $request->content;
        $article->published = $request->published;
        $article->tags = $request->tags;
        $article->categories()->sync($request->categories);
        $article->save();
        return response()->json([
            'status' => 'success',
            'article' => $article,
        ]);
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article = Article::where('id', $id)->where('user_id', auth()->user()->id)->first()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Article not found',
            ]);
        }

        $article->delete();
        return response()->json([
            'status' => 'success',
            'article' => $article,
        ]);
    }

    public function search(Request $request)
    {
        $articles = Article::where('title', 'like', '%' . $request->search . '%')->where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $articles,
        ]);
    }

    public function searchByTag(Request $request)
    {
        $articles = Article::where('tags', 'like', '%' . $request->search . '%')->where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $articles,
        ]);
    }

    public function searchByCategory(Request $request)
    {
        $articles = Article::whereHas('categories', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $articles,
        ]);
    }

    public function searchByUser(Request $request)
    {
        $articles = Article::whereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $articles,
        ]);
    }

    public function searchByDate(Request $request)
    {
        $articles = Article::where('created_at', 'like', '%' . $request->search . '%')->where('published', 1)->get();
        return response()->json([
            'status' => 'success',
            'articles' => $articles,
        ]);
    }
}
