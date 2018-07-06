<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Events;

class EventsController extends Controller
{
    public function byMonth(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        
        if (is_null($year) or is_null($month))
        {
            return response()->json([], 400);
        }
        
        if ($month>12 or $month<1)
        {
            return response()->json([], 400);
        }
        
        $monthStart = mktime(0, 0, 0, $month, 1, $year);
        $monthEnd = mktime(0, 0, 0, $month+1, 1, $year);

        // Find overlap
        // https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-two-integer-ranges-for-overlap
        $overlap = Events::whereDate('date_from', '<=', date('Y-m-d', $monthEnd))->whereDate('date_to', '>=', date('Y-m-d', $monthStart));

        $events = $overlap->orderBy('date_from')->get();

        if ($events->isEmpty())
        {
            return response()->json([], 404);
        }
        return $events;
    }
}
