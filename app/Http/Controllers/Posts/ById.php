<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait ByID
{
    public function byId(Request $request)
    {
        $postId = $request->input('id');
        if (is_null($postId)) {
            return response()->json([], 400);
        }

        $post = self::_resolvedPosts()->where('id', '=', $postId)->get()->first();

        $maxDate = null;
        if (!is_null($post)) {
            $maxDate = $post->updated_at;
        }

        return self::_after($request, $post, $maxDate);
    }
}
