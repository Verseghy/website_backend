<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait ByLabel
{
    public function byLabel(Request $request)
    {
        $labelId = $request->input('id');

        if (is_null($labelId)) {
            return response()->json([], 400);
        }

        $posts = self::_resolvedPosts()->whereHas('labels', function ($query) use ($labelId) {
            $query->where('id', '=', $labelId);
        });

        return self::_after($request, $posts);
    }
}
