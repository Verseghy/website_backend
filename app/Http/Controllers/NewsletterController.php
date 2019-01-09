<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $newsletter = new Newsletter();

        if (is_null($request->input('email'))) {
            return response()->json([], 400);
        }

        $newsletter->email = $request->input('email');

        if (is_null($request->input('mldata'))) {
            return response()->json([], 400);
        }

        $newsletter->mldata = $request->input('mldata');

        if (!Newsletter::where('email', $request->input('email'))->get()->isEmpty()) {
            return response()->json([], 409);
        }

        $newsletter->token = str_random(32);

        if ($newsletter->save()) {
            return response()->json([], 200);
        } else {
            //@codeCoverageIgnoreStart
            return response()->json([], 500);
            //@codeCoverageIgnoreEnd
        }
    }

    public function unsubscribe(Request $request)
    {
        if (is_null($request->input('email'))) {
            return response()->json([], 400);
        }
        if (is_null($request->input('token'))) {
            return response()->json([], 400);
        }

        if (Newsletter::where('email', $request->input('email'))->where('token', $request->input('token'))->delete()) {
            return response()->json([], 200);
        } else {
            if (Newsletter::where('email', $request->input('email'))->where('token', $request->input('token'))->get()->isEmpty()) {
                if (Newsletter::where('email', $request->input('email'))->get()->isEmpty()) {
                    return response()->json([], 400);
                } else {
                    return response()->json([], 401);
                }
            } else {
                //@codeCoverageIgnoreStart
                return response()->json([], 500);
                //@codeCoverageIgnoreEnd
            }
        }
    }
}
