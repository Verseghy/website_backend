<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Canteens;
use App\Models\Canteens\Menus;

class CanteensController extends Controller
{
    use ControllerBase;

    public function getMenus(Request $request)
    {
        $type = $request->input('id');

        if (is_null($type)) {
            return response()->json([], 400);
        } elseif ($type < 0 or $type > 2) {
            return response()->json([], 400);
        }

        $menus = Menus::where('type', '=', $type)->orderBy('menu');

        return self::_after($request, $menus, null);
    }

    public function byWeek(Request $request)
    {
        $year = $request->input('year');
        $week = $request->input('week');

        if (is_null($year) or is_null($week)) {
            return response()->json([], 400);
        }

        $date = new Carbon();
        $date->setIsoDate($year, $week);

        $start = $date->startOfWeek()->toDateString().' 00:00:00';
        $end = $date->endOfWeek()->toDateString().' 23:59:59';

        $canteens_query = Canteens::with('menus')->whereBetween('date', [$start, $end])->orderBy('date');

        // order canteens by date

        $canteens = $canteens_query->get()->toArray();
        if (0 !== count($canteens)) {
            $maxDate = $canteens_query->latest('updated_at')->first()->updated_at;
        }
        $canteens_sorted = array();
        foreach ($canteens as $canteen) {
            usort($canteen['menus'], function ($item1, $item2) {
                return $item1['type'] <=> $item2['type'];
            });

            array_push($canteens_sorted, $canteen);
        }

        return $canteens_sorted;
    }
}
