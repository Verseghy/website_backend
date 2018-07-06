<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Canteens;
use App\Models\Canteens\Menus;

class CanteensController extends Controller
{
    public function getMenus(Request $request)
    {
        $type = $request->input('id');
        return Menus::where('type', '=', $type)->orderBy('menu')->get();
    }

    public function byWeek(Request $request)
    {
        $year = $request->input('year');
        $week = $request->input('week');
        
        $date = new Carbon;
        $date->setIsoDate($year, $week);
        
        $start = $date->startOfWeek()->toDateString();
        $end = $date->endOfWeek()->toDateString();
        
        return Canteens::with('menus')->whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->orderBy('date')->get();
    }
}
