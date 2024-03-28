<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
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
        // verify payload data
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $event = $payload['event'] ?? null;
        $entityUniqueId = $payload['entityUniqueId'] ?? null;

        if (!$event || !$entityUniqueId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        $noviConnector = new NoviConnector;
        $doceboConnector =  new DoceboConnector;
        // verify memberDetail From Novi
        $memberDataResponse = $noviConnector->send( new GetMemberDetailFromNovi($entityUniqueId));
        $noviUserData = $memberDataResponse->dto();

        if($noviUserData['email']){

            $doceboUserDataResponse = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserData['email']));
            $doceboUserData = $doceboUserDataResponse->dto();

            if($doceboUserData){
                $doceboConnector->send(new UpdateUserFiledsData($doceboUserData, $noviUserData['details']));
                Log::warning('["NOVI AMS"][DOCEBO LMS][customer.updated ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Updated successfully in docebo');
            }else{
                Log::warning('["NOVI AMS"][DOCEBO LMS][customer.updated ]: Entity NOVI Unique ID: ' . $entityUniqueId . 'The Email not found on DOCEBO');
            }

            return response()->json(['status' => 'success'] , 200);
        }else{
            Log::warning('["NOVI AMS"][NOVI AMS][ customer.updated ]: Entity NOVI Unique ID: ' . $entityUniqueId . 'The Email is empty on NOVI AMS');
            return response()->json(['status' => 'success'] , 200);
        }


    }
}
