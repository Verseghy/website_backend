<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Markdown;
use App\Http\Controllers\Posts\ById;
use App\Http\Controllers\Posts\GetPreview;
use App\Http\Controllers\Posts\ListFeatured;
use App\Http\Controllers\Posts\ByAuthor;
use App\Http\Controllers\Posts\Archive;
use App\Http\Controllers\Posts\ListPosts;
use App\Http\Controllers\Posts\ByLabel;
use App\Http\Controllers\Posts\Search;

class PostsController extends Controller
{
    use ControllerBase
    {
        _after as _after_original;
    }
    const PAGESIZE = 20;

    use ById;
    use GetPreview;
    use ListFeatured;
    use ByAuthor;
    use Archive;
    use ListPosts;
    use ByLabel;
    use Search;

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

        $parser = Markdown::instance()->setBreaksEnabled(true)->setMarkupEscaped(true)->setUrlsLinked(false);

        $post['content'] = isset($post['content']) ? $parser->text($post['content']) : '';

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
