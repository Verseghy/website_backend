<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colleagues;
use Parsedown;


class ColleaguesController extends Controller
{
    //
    public function listColleagues(Request $request)
    {
        $colleagues=Colleagues::orderBy('name')->get();
        return $colleagues;
    }

}
