<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Models\Posts;
use DB;
use Carbon\Carbon;

trait Archive
{
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
}
