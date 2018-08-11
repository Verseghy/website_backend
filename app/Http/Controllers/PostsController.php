<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use Carbon\Carbon;

use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;

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
            return self::_after($request, Posts::orderby('date', 'desc')->take(3));
        }
        
        $everyPost = Posts::all()->getDictionary();
        $jsonData = json_decode($userRating, true);
        
        $categoriesVector = array();
        $ratingVector = array();

        foreach (array_keys($jsonData) as $id) {
            $postId = intval($id);
            $post = $everyPost[$postId];
            
            // we can not be sure that the post even exists
            if (isset($post)) {
                $postCategoryVector = json_decode($post->mldata);
                $userScore = $jsonData[$id];
                
                // and we can not be sure that the post has a valid 'mldata' field
                if (isset($postCategoryVector) && isset($userScore)) {
                    array_push($categoriesVector, $postCategoryVector);
                    array_push($ratingVector, $userScore);
                    unset($everyPost[$postId]);
                }
            }
        }
        
        // At this point $categoriesVector and $ratingVectory should be prepared to feed into ML functions
        
        $regression = new SVR(Kernel::LINEAR);
        $regression->train($categoriesVector, $ratingVector);


        $predicts = array();

        foreach ($everyPost as $post) {
            $postCategoryVector = json_decode($post->mldata);
            
            // posts without a category vector won't get in
            if (isset($postCategoryVector)) {
                $prediction = $regression->predict($postCategoryVector);
                array_push($predicts, array($post, $prediction));
            }
        }

        usort($predicts, function ($a, $b) {
            if ($a[1] == $b[1]) {
                return ($a[0]->date > $b[0]->date) ? -1 : 1;
            }
            return ($a[1] > $b[1]) ? -1 : 1;
        });
  
        $first3 = array_slice($predicts, 0, 3);
  
        return $first3;
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
