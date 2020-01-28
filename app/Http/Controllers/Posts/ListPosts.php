<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait ListPosts
{
    public function listPosts(Request $request)
    {
        $posts = self::_resolvedPosts();

        return self::_after($request, $posts, null, false);
    }
}
