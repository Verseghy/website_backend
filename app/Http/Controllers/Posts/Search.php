<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait Search
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('term');

        if (is_null($searchTerm)) {
            return response()->json([], 400);
        }
        $posts = self::_resolvedPosts()->where(function ($query) use ($searchTerm) {
            $query->where('content', 'like', '%'.$searchTerm.'%')
            ->orWhere('description', 'like', '%'.$searchTerm.'%')
            ->orWhere('title', 'LIKE', "%$searchTerm%");
        });

        return self::_after($request, $posts);
    }
}
