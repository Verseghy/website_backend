<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use Carbon\Carbon;

class EventsController extends Controller
{
    use ControllerBase;

    public function byMonth(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        if (is_null($year) or is_null($month)) {
            return response()->json([], 400);
        }

        if ($month > 12 or $month < 1) {
            return response()->json([], 400);
        }

        $monthStart = Carbon::create($year, $month, 1, 0, 0, 0);
        $monthEnd = Carbon::create($year, $month + 1, 1, 0, 0, 0);

        // Find overlap
        // https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-two-integer-ranges-for-overlap
        $overlap = Events::whereDate('date_from', '<=', $monthEnd->toDateString())->whereDate('date_to', '>=', $monthStart->toDateString());

        $events = $overlap->orderBy('date_from');

        return self::_after($request, $events, null);
    }
}
