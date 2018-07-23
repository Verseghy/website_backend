<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait ControllerBase
{
    protected static function _after($request, $result, $maxDate)
    {
        if (is_null($maxDate)) {
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $result;
                $result = $query->get();
                
                if (!$result->isEmpty()) {
                    $maxDate = $query->latest('updated_at')->first()->updated_at;
                }
            }
        }
    
        
        // check for null
        if (is_null($result)) {
            return response()->json([], 404);
        }
    
    
        // check for empty arrays
        if ($result instanceof \Illuminate\Database\Eloquent\Collection) {
            if ($result->isEmpty()) {
                return response()->json([], 404);
            }
        }
        
        
        $modSince = self::_modSince($request);
        if ($maxDate->lte($modSince)) {
            return response()->json([], 304);
        }
        return response()->json($result)->withHeaders(['Last-modified'=>$maxDate->toRfc7231String()]);
    }
    
    protected static function _modSince($request)
    {
        return new Carbon(str_replace(':', '', $request->header('If-modified-since', ': 1970-01-01')));
    }
}
