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
        
        $monthStart = mktime(0,0,0,$month,1,$year);
        $monthEnd = mktime(0,0,0,$month+1,0,$year);

        // Find overlap
        // https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-two-integer-ranges-for-overlap        
        $overlap = Events::whereDate('date_from','<=',date('Y-m-d',$monthEnd))->whereDate('date_to','>=',date('Y-m-d',$monthStart));

        return $overlap->orderBy('date_from')->get();
    }
}
