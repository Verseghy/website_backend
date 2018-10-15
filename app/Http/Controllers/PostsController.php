<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use Carbon\Carbon;
use Parsedown;

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
    
    public function listFeaturedPosts(Request $request)
    {
        $selected_number=0;
        $selected= array();
        $posts = self::_resolvedPosts()->orderBy('date','desc')->get();
        foreach ($posts as $post) {
            if($post->featured == true and $selected_number<3)
            {
                $selected[$selected_number] = $post;
                $selected_number++;
            }
            if($selected_number==3)
            {
                break;
            }
        }
        if($selected_number<3)
        {
            foreach ($posts as $post) {
                if($post->featured == false and $selected_number<3)
                {
                    $selected[$selected_number] = $post;
                    $selected_number++;
                }
                if($selected_number==3)
                {
                    break;
                }
            }
        }
        return $selected;

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
        //var_dump($result);
        if ($result instanceof \Illuminate\Database\Eloquent\Collection) {
            $result = array_map("self::_expandUrls", $result->toArray());
        } elseif ($result instanceof Posts) {
            $result = self::_expandUrls($result);
        }

        return self::_after_original($request, $result, $maxDate);
    }

    private static function _resolvedPosts()
    {
        return Posts::with(['author', 'labels'])->orderBy('date', 'desc');
    }

    private static function _expandUrls($post)
    {
        if ($post instanceof Posts) {
            $post = $post->toArray();
        }
        assert(is_array($post));

        $parser = Parsedown::instance()->setBreaksEnabled(true)->setMarkupEscaped(true)->setUrlsLinked(false);
        $post['content'] = isset($post['content']) ? $parser->text($post['content']) : "";
        
        $post['index_image'] = isset($post['index_image']) ? self::_publicUrl($post['index_image']) : null;
        $post['author']['image'] = isset($post['author']['image']) ? self::_publicUrl($post['author']['image'], 'authors_images') : null;
        if (isset($post['images'])) {
            $post['images'] = array_map(function ($f) {
                return self::_publicUrl($f);
            }, $post['images']);
        }
        return $post;
    }
    
    private static function _publicUrl($file, $disk='posts_images')
    {
        return asset(\Storage::disk($disk)->url($file));
    }

    private static function _makeThumbnail($posts)
    {
        return $posts->paginate(self::PAGESIZE)->makeHidden(['content']);
    }
}
