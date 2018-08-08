<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use Carbon\Carbon;

class PostsController extends Controller
{
    use ControllerBase
    {
        _after as _after_original;
    }
    const PAGESIZE = 20;

    public function byId(Request $request)
    {
        $postId = $request->input('id');
        if (is_null($postId)) {
            return response()->json([], 400);
        }
        
        $post = self::_resolvedPosts()->where('id', '=', $postId)->get()->first();
        
        $maxDate = null;
        if (!is_null($post)) {
            $maxDate=$post->updated_at;
        }
        
        return self::_after($request, $post, $maxDate);
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
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('term');
        
        if (is_null($searchTerm)) {
            return response()->json([], 400);
        }
        $posts = self::_resolvedPosts()->where('content', 'like', '%'.$searchTerm.'%')->orWhere('description', 'like', '%'.$searchTerm.'%')->orWhere('title', 'LIKE', "%$searchTerm%");
        
        return self::_after($request, $posts);
    }

    public function recommend(Request $request)
    {
        $userRating = $request->input('mldata');

        if (is_null($userRating)) {
            return self::_after($request,Posts::orderby('date','desc')->take(3));
        }
        
        $everyPost = Posts::all()->getDictionary();
        $jsonData = json_decode($userRating,true);
        
        $categoriesVector = array();
        $ratingVector = array();

        foreach (array_keys($jsonData) as $id)
        {
            $postId = intval($id);
            array_push($categoriesVector, json_decode($everyPost[$postId]->mldata));
            array_push($ratingVector, $jsonData[$id]);
            unset($everyPost[$postId]);
        }
        
        //TODO: Train neural network
        //TODO: Do predictions
        var_dump($categoriesVector);
        var_dump($ratingVector);
        return "";
        
        
        return response()->json(['Not implemented :-('], 501);
    }


    protected static function _after($request, $result, $maxDate = null)
    {
        if (is_null($maxDate)) {
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $maxDate = $result->latest('updated_at')->first();
                
                $maxDate = null;
                if (!is_null($query)) {
                    $maxDate = $query->updated_at;
                }
                
                $result = self::_makeThumbnail($result);
            }
        }
        
        return self::_after_original($request, $result, $maxDate);
    }

    private static function _resolvedPosts()
    {
        return Posts::with(['index_image','author','author.image','images','labels'])->orderBy('date', 'desc');
    }

    private static function _makeThumbnail($posts)
    {
        return $posts->paginate(self::PAGESIZE)->makeHidden(['images','content']);
    }
}
