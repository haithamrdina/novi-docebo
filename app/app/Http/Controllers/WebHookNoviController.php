<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class WebHookNoviController extends Controller
{
     /**
    * @param Request $request
    * @return json
    */
    public function noviUpdateHandle(Request $request)
    {
        Log::info('Webhook Payload: ');
        return response()->json(['status' => 'success']);
    }
}
