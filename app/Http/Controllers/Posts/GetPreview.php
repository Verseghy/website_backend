<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait GetPreview
{
    public function getPreview(Request $request)
    {
        $postId = $request->input('id');
        $token = $request->input('token');
        if (is_null($postId)) {
            return response()->json([], 400);
        }

        $post = self::_resolvedPosts(true)->where('published', '!=', true)->where('id', '=', $postId)->get()->first();

        if (empty($post)) {
            return response()->json([], 404);
        }

        if (empty($token) || $token != $post->previewToken) {
            return response()->json([], 401);
        }

        $maxDate = null;
        if (!is_null($post)) {
            $maxDate = $post->updated_at;
        }

        return self::_after($request, $post, $maxDate);
    }
}
