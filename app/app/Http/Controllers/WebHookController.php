<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersData;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Docebo\Requests\UpdateUserStatusFromDocebo;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\AddNewMember;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    /**
    * @param Request $request
    * @return json
    */
    public function webhookNoviHandler(Request $request){
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $event = $payload['event'] ?? null;
        $entityUniqueId = $payload['entityUniqueId'] ?? null;

        if (!$event || !$entityUniqueId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        switch ($event) {
            case 'customer.updated':
                $result = $this->costumerUpdated($entityUniqueId);
                if($result){
                    Log::info('["NOVI AMS"][ '. $event .' ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Updated successfully in docebo');
                }else{
                    Log::error('["NOVI AMS"][ '. $event .' ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Unexpected error on Update');
                }
                break;
            case 'customer.removed':
                $result = $this->costumerRemoved($entityUniqueId);
                if($result){
                    Log::info('["NOVI AMS"][ '. $event .' ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Archived successfully in docebo');
                }else{
                    Log::error('["NOVI AMS"][ '. $event .' ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Unexpected error on Archive');
                }
                break;
            default:
                break;
        }
        return response()->json(['status' => 'success']);
    }

    public function costumerUpdated($entityUniqueId){
        $noviConnector = new NoviConnector;
        $doceboConnector =  new DoceboConnector;

        $memberDataResponse = $noviConnector->send( new GetMemberDetailFromNovi($entityUniqueId));
        $noviUserData = $memberDataResponse->dto();

        if($noviUserData['email']){
            $doceboUserDataResponse = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserData['email']));
            $doceboUserData = $doceboUserDataResponse->dto();

            $result = false;
            if($doceboUserData){
                $doceboConnector->send(new UpdateUserFiledsData($doceboUserData, $noviUserData['details']));
                $result= true;
            }else{
                Log::error('["NOVI AMS"][ customer.updated ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Not found in docebo');
            }

            return $result;
        }else{
            Log::error('["NOVI AMS"][ customer.updated ]: Entity NOVI Unique ID: ' . $entityUniqueId . ' Email not found in NOVI AMS');
        }
    }

    public function costumerRemoved($entityUniqueId){
        $noviConnector = new NoviConnector;
        $doceboConnector =  new DoceboConnector;

        $memberDataResponse = $noviConnector->send( new GetMemberDetailFromNovi($entityUniqueId));
        $noviUserData = $memberDataResponse->dto();

        $doceboUserDataResponse = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserData['email']));
        $doceboUserData = $doceboUserDataResponse->dto();
        $result = false;
        if($doceboUserData){
            $doceboConnector->send(new UpdateUserStatusFromDocebo($doceboUserData));
            $result = true;
        }

        return $result;
    }


    /**
    * @param Request $request
    * @return json
    */
    public function webhookDoceboHandler(Request $request){
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $event = $payload['event'] ?? null;
        $email = $payload['payload']['username'] ?? null;
        $doceboId = $payload['payload']['user_id'] ?? null;

        if (!$event || !$email || !$doceboId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        if($event == "user.created"){
            $result = $this->userCreated($email);
            if($result){
                Log::info('["DOCEBO LMS"][ '. $event .' ]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Added successfully in Novi');
            }else{
                Log::error('["DOCEBO LMS"][ '. $event .' ]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Unexpected error');
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function userCreated($email){

        $result = false;
        $doceboConnector =  new DoceboConnector;
        $noviConnector = new NoviConnector;
        $doceboUsersDataResponse = $doceboConnector->send(new GetUsersData($email));
        $doceboUserData = $doceboUsersDataResponse->dto();
        if($doceboUserData){
            $noviConnector->send(new AddNewMember($doceboUserData));

            $result = true;
        }
        return $result;
    }


    /**
    * @param Request $request
    * @return json
    */
    public function noviUpdateHandle(Request $request)
    {
        $payload = $request->getContent();
        Log::info('Webhook Payload: ' . $payload);
        return response()->json(['status' => 'success']);
    }

}
