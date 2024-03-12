<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Docebo\Requests\UpdateUserStatusFromDocebo;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersDataFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersSimpleDataFromNovi;
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

        $doceboUserDataResponse = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserData['email']));
        $doceboUserData = $doceboUserDataResponse->dto();

        $result = false;
        if($doceboUserData){
            $doceboConnector->send(new UpdateUserFiledsData($doceboUserData, $noviUserData['details']));
            $result= true;
        }

        return $result;
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
            $result = $this->userCreated($email, $doceboId);
            if($result){
                Log::info('["DOCEBO LMS"][ '. $event .' ]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Updated successfully in docebo');
            }else{
                Log::error('["DOCEBO LMS"][ '. $event .' ]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Unexpected error');
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function userCreated($email, $doceboId){

        $result = false;
        $doceboConnector =  new DoceboConnector;
        $noviConnector = new NoviConnector;
        $noviUsersDataResponse = $noviConnector->send(new GetUsersDataFromNovi($email));
        $noviUserData = $noviUsersDataResponse->dto();
        if($noviUserData){
            $doceboConnector->send(new UpdateUserFiledsData($doceboId, $noviUserData));
            $result = true;
        }

        return $result;
    }

}
