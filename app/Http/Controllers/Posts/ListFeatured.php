<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;

trait ListFeatured
{
    public function listFeaturedPosts(Request $request)
    {
        $NUMBER_TO_RETURN = 5;

        $result = self::_resolvedPosts()->where('featured', '=', true)->orderBy('date', 'desc')->take($NUMBER_TO_RETURN)->get();
        $count = $result->count();
        if ($count !== $NUMBER_TO_RETURN) {
            $nonFeatured = self::_resolvedPosts()->where('featured', '=', false)->orderBy('date', 'desc')->take($NUMBER_TO_RETURN - $count)->get();
            $result = $result->merge($nonFeatured);
        }

        return self::_after($request, $result);
    }
}
