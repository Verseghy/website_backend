<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait ByAuthor
{
    public function byAuthor(Request $request)
    {
        $authorId = $request->input('id');

        if (is_null($authorId)) {
            return response()->json([], 400);
        }

        $posts = self::_resolvedPosts()->where('author_id', '=', $authorId);

        return self::_after($request, $posts);
    }
}
