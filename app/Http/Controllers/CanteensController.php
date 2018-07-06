<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Canteens;
use App\Models\Canteens\Menus;

class CanteensController extends Controller
{
    public function getMenus(Request $request)
    {
        $type = $request->input('id');
        return Menus::where('type', '=', $type)->orderBy('menu')->get();
    }
}
