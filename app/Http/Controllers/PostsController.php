<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Markdown;
use DB;
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
            $maxDate = $post->updated_at;
        }

        return self::_after($request, $post, $maxDate);
    }

    public function getPreview(Request $request)
    {
        $postId = $request->input('id');
        $token = $request->input('token');
        if (is_null($postId)) {
            return response()->json([], 400);
        }

        $post = self::_resolvedPosts(true)->where('published', '!=', true)->where('id', '=', $postId)->get()->first();

        if (empty($post)) {
            return response()->json([], 404);
        }

        if (empty($token) || $token != $post->previewToken) {
            return response()->json([], 401);
        }

        $maxDate = null;
        if (!is_null($post)) {
            $maxDate = $post->updated_at;
        }

        return self::_after($request, $post, $maxDate);
    }

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

    public function byAuthor(Request $request)
    {
        $authorId = $request->input('id');

        if (is_null($authorId)) {
            return response()->json([], 400);
        }

        $posts = self::_resolvedPosts()->where('author_id', '=', $authorId);

        return self::_after($request, $posts);
    }

    public function byYearMonth(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        if (is_null($year) || is_null($month)) {
            return response()->json([], 400);
        }

        $posts = self::_resolvedPosts()->whereMonth('date', '=', $month)->whereYear('date', '=', $year);

        return self::_after($request, $posts);
    }

    public function listPosts(Request $request)
    {
        $posts = self::_resolvedPosts();

        return self::_after($request, $posts, null, false);
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
        $posts = self::_resolvedPosts()->where(function ($query) use ($searchTerm) {
            $query->where('content', 'like', '%'.$searchTerm.'%')
            ->orWhere('description', 'like', '%'.$searchTerm.'%')
            ->orWhere('title', 'LIKE', "%$searchTerm%");
        });

        return self::_after($request, $posts);
    }

    public function countByMonth(Request $request)
    {
        $data = Posts::select(DB::raw('(COUNT(*)) as count'), DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'))
        ->where('published', true)
        ->groupBy(DB::raw('year'), DB::raw('month'))
        ->orderBy('year', 'desc')->orderBy('month', 'desc')
        ->get();

        $maxDate = $data->count() ? Carbon::parse(Posts::latest()->first()->date) : null;
        $modSince = self::_modSince($request);
        if (isset($maxDate) && $maxDate->lte($modSince)) {
            return response()->json([], 304);
        }

        return $data;
    }

    protected static function _after($request, $result, $maxDate = null, $makeThumbnail = true)
    {
        if (is_null($maxDate)) {
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $maxDate = $result->latest('updated_at')->first();

                $maxDate = null;
                if (!is_null($query)) {
                    $maxDate = $query->updated_at;
                }
                $result = self::_makeThumbnail($result, $makeThumbnail);
            }
        }
        //var_dump($result);
        if ($result instanceof \Illuminate\Database\Eloquent\Collection) {
            $result = array_map('self::_expandUrls', $result->toArray());
        } elseif ($result instanceof Posts) {
            $result = self::_expandUrls($result);
        }

        return self::_after_original($request, $result, $maxDate);
    }

    private static function _resolvedPosts($all = false)
    {
        $posts = Posts::with(['author', 'labels'])->orderBy('date', 'desc');
        if (!$all) {
            $posts = $posts->where('published', true);
        }

        return $posts;
    }

    private static function _expandUrls($post)
    {
        if ($post instanceof Posts) {
            $post = $post->toArray();
        }
        assert(is_array($post));

        $post['index_image'] = isset($post['index_image']) ? self::_publicUrl($post['index_image']) : null;
        $post['author']['image'] = isset($post['author']['image']) ? self::_publicUrl($post['author']['image'], 'authors_images') : null;
        if (isset($post['images'])) {
            $post['images'] = array_map(function ($f) {
                return self::_publicUrl($f);
            }, $post['images']);
        }

        return $post;
    }

    private static function _publicUrl($file, $disk = 'posts_images')
    {
        return asset(\Storage::disk($disk)->url($file));
    }

    private static function _makeThumbnail($posts, $enabled = true)
    {
        $result = $posts->paginate(self::PAGESIZE);
        if ($enabled) {
            $result = $result->makeHidden(['content']);
        } else {
            $result = $result->makeHidden([]);
        }

        return $result;
    }
}
