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
        
        
        
        return self::_after($request, $menus);
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
        
        
        
        return self::_after($request, $canteens);
    }
    
    
    private static function _modSince($request)
    {
        return new Carbon(str_replace(':', '', $request->header('If-modified-since', ': 1970-01-01')));
    }
    
    private function _after($request, $result)
    {
        
        if ($result->isEmpty()) {
            return response()->json([], 404);
        }
        
        
        $modSince = self::_modSince($request);
        
        
        // Find max date
        $maxDate = new Carbon('1970-01-01');
        $result->each(function ($post) use ($maxDate) {
            if ($maxDate->lt($post->updated_at)) {
                // set the time
                // Sadly, Carbon::setTimeFrom(Carbon $other) does not seem to exist, only in the docs
                $maxDate->timestamp = $post->updated_at->timestamp;
            }
        });

        if ($maxDate->lte($modSince)) {
            return response()->json([], 304);
        }
        
        return $result;
    }
    
}
