<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Events;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function byMonth(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        
        if (is_null($year) or is_null($month)) {
            return response()->json([], 400);
        }
        
        if ($month>12 or $month<1) {
            return response()->json([], 400);
        }
        
        $monthStart = mktime(0, 0, 0, $month, 1, $year);
        $monthEnd = mktime(0, 0, 0, $month+1, 1, $year);

        // Find overlap
        // https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-two-integer-ranges-for-overlap
        $overlap = Events::whereDate('date_from', '<=', date('Y-m-d', $monthEnd))->whereDate('date_to', '>=', date('Y-m-d', $monthStart));

        $events = $overlap->orderBy('date_from')->get();

        return self::_after($request, $events);
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
