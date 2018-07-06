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
        
        if (is_null($type)) {
            return response()->json([], 400);
        } elseif ($type<0 or $type>2) {
            return response()->json([], 400);
        }
        
        $menus = Menus::where('type', '=', $type)->orderBy('menu')->get();
        
        if ($menus->isEmpty()) {
            return repsponse()->json([], 404);
        }
        
        return $menus;
    }

    public function byWeek(Request $request)
    {
        $year = $request->input('year');
        $week = $request->input('week');
        
        if (is_null($year) or is_null($week)) {
            return response()->json([], 400);
        }
        
        $date = new Carbon;
        $date->setIsoDate($year, $week);
        
        $start = $date->startOfWeek()->toDateString() . ' 00:00:00';
        $end = $date->endOfWeek()->toDateString() .' 00:00:00';
        
        $canteens = Canteens::with('menus')->whereBetween('date', [$start,$end])->orderBy('date')->get();
        
        if ($canteens->isEmpty()) {
            return response()->json([], 404);
        }
        
        return $canteens;
    }
}
