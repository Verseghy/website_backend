<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

class PostsController extends Controller
{
    public function byId(Request $request)
    {
        $postId = $request->input('id');
        $post = Posts::with(['index_image','author','author.image','images'])->where('id', '=', $postId)->get()->first();
        if (is_null($post)) {
            return response()->json([], 404);
        } else {
            return $post;
        }
    }
}
