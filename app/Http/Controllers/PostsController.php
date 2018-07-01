<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

class PostsController extends Controller
{
    const PAGESIZE = 20;

    private static function _resolvedPosts()
    {
        return Posts::with(['index_image','author','author.image','images','labels'])->orderBy('date', 'desc');
    }

    private static function _makeThumbnail($posts)
    {
        return $posts->makeHidden(['images','content']);
    }

    public function byId(Request $request)
    {
        $postId = $request->input('id');
        $post = self::_resolvedPosts()->where('id', '=', $postId)->get()->first();
        if (is_null($post)) {
            return response()->json([], 404);
        } else {
            return $post;
        }
    }
}
