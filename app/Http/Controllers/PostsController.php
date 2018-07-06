<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

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
        return $post;
    }
    
    public function byAuthor(Request $request)
    {
        $authorId = $request->input('id');
        
        if (is_null($authorId)) {
            return response()->json([], 400);
        }
        
        $posts=self::_resolvedPosts()->where('author_id', '=', $authorId);
        $posts = self::_makeThumbnail($posts);
        
        return self::_dataOr404($posts);
    }
    
    public function listPosts(Request $request)
    {
        $posts=self::_resolvedPosts();
        $posts = self::_makeThumbnail($posts);
        
        return self::_dataOr404($posts);
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
        
        $posts = self::_makeThumbnail($posts);
        
        return self::_dataOr404($posts);
    }

    private static function _resolvedPosts()
    {
        return Posts::with(['index_image','author','author.image','images','labels'])->orderBy('date', 'desc');
    }

    private static function _makeThumbnail($posts)
    {
        return $posts->paginate(self::PAGESIZE)->makeHidden(['images','content']);
    }
    
    private static function _dataOr404($data)
    {
        if ($data->isEmpty()) {
            return response()->json([], 404);
        } else {
            return $data;
        }
    }
}
