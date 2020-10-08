<?php

namespace App\Http\Controllers;

use Arr;
use Backpack\MenuCRUD\app\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenuItems(Request $request)
    {
        $data = collect([]);
        foreach (MenuItem::getTree() as $item) {
            $data->push($this->formatMenuTree($item));
        }

        return $data;
    }

    private function formatMenuTree($node)
    {
        $data = [];
        Arr::set($data, 'name', $node->name);
        Arr::set($data, 'slug', $node->page->slug);
        Arr::set($data, 'link', $node->link);
        Arr::set($data, 'type', $node->type);
        Arr::set($data, 'children', collect([]));

        foreach ($node->children as $child) {
            Arr::get($data, 'children')->push($this->formatMenuTree($child));
        }

        return $data;
    }
}
