<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('isAdmin')->only(['destroy', 'update', 'store']);
    }

    public function index()
    {
        $ads = Ads::all();
        return response()->json([
            'status' => 'success',
            'ads' => $ads,
        ]);
    }

    public function show($id)
    {
        $ad = Ads::find($id);
        return response()->json([
            'status' => 'success',
            'ad' => $ad,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ads',
            'description' => 'required|string|max:255',
            'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $ad = new Ads;
        $ad->title = $request->title;
        $ad->slug = $request->slug;
        $ad->description = $request->description;
        $ad->picture = $request->picture;
        $ad->published = $request->published;
        $ad->article_id = $request->article_id ?? null;
        $ad->user_id = auth()->user()->id;

        $file = $request->file('picture');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('/images/ads'), $fileName);
        $ad->picture = $fileName;

        $ad->save();
        return response()->json([
            'status' => 'success',
            'ad' => $ad,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ads,slug,' . $id,
            'description' => 'required|string|max:255',
            'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $ad = Ads::find($id);
        $ad->title = $request->title;
        $ad->slug = $request->slug;
        $ad->description = $request->description;
        $ad->picture = $request->picture;
        $ad->published = $request->published;
        $ad->article_id = $request->article_id ?? null;
        $ad->user_id = auth()->user()->id;
        $ad->save();
        return response()->json([
            'status' => 'success',
            'ad' => $ad,
        ]);
    }

    public function destroy($id)
    {
        $ad = Ads::find($id);
        $ad->delete();
        return response()->json([
            'status' => 'success',
            'ad' => $ad,
        ]);
    }
}
