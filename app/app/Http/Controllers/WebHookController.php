<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    /**
    * @param Request $request
    * @return json
    */
   public function webhookNoviUpdateHandler(Request $request){
        $payload = $request->getContent();
        Log::info('Webhook Payload: ' . $payload);
        return response()->json(['status' => 'success']);
   }
}
