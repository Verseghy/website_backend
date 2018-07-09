<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use Carbon\Carbon;

class PostsController extends Controller
{
    const PAGESIZE = 20;

    public function byId(Request $request)
    {
        $postId = $request->input('id');
        if (is_null($postId)) {
            return response()->json([], 400);
        }
        
        $post = self::_resolvedPosts()->where('id', '=', $postId)->get()->first();
        if (is_null($post)) {
            return response()->json([], 404);
        }
        
        $checkDate = self::_modSince($request);
        
        if ($post->updated_at->lte($checkDate)) {
            return response()->json([], 304);
        }
        
        return $post;
    }
    
    public function byAuthor(Request $request)
    {
        $authorId = $request->input('id');
        
        if (is_null($authorId)) {
            return response()->json([], 400);
        }
        
        $posts=self::_resolvedPosts()->where('author_id', '=', $authorId);
        
        
        return self::_after($request, $posts);
    }
    
    public function listPosts(Request $request)
    {
        $posts=self::_resolvedPosts();
        
        return self::_after($request, $posts);
    }
    
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

    private static function _resolvedPosts()
    {
        return Posts::with(['index_image','author','author.image','images','labels'])->orderBy('date', 'desc');
    }

    private static function _makeThumbnail($posts)
    {
        return $posts->paginate(self::PAGESIZE)->makeHidden(['images','content']);
    }

    private static function _modSince($request)
    {
        return new Carbon(str_replace(':', '', $request->header('If-modified-since', ': 1970-01-01')));
    }
    
    private static function _after($request, $result)
    {
        $result = self::_makeThumbnail($result);
        
        if ($result->isEmpty()) {
            return response()->json([], 404);
        }
    
        $modSince = self::_modSince($request);
        
        
        // Find max date
        $maxDate = new Carbon('1970-01-01');
        $result->each(function ($post) use ($maxDate) {
            if ($maxDate->lt($post->updated_at)) {
                // set the time
                // Sadly, Carbon::setTimeFrom(Carbon $other) does not seem to exist, only in the docs
                $maxDate->timestamp = $post->updated_at->timestamp;
            }
        });

        if ($maxDate->lte($modSince)) {
            return response()->json([], 304);
        }
        
        return $result;
    }
}
