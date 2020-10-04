<?php

namespace App\Http\Controllers;

use Backpack\PageManager\app\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getPageBySlug(Request $request)
    {
        $page = Page::findBySlug($request->input('slug'));

        if (!$page) {
            return response()->json([], 400);
        }

        return response()->json($page, 200);
    }
}
