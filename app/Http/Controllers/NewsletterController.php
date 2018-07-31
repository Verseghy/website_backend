<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $newsletter = new Newsletter;

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

        //Generate random token
        //https://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < 32; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        $newsletter->token = $token;

        if ($newsletter->save()) {
            return response()->json([], 200);
        } else {
            return response()->json([], 500);
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
                return response()->json([], 500);
            }
        }
    }
}
