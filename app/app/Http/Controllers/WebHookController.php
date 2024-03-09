<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Docebo\Requests\UpdateUserStatusFromDocebo;
use App\Http\Integrations\Novi\NoviConnector;
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
                $this->costumerUpdated($entityUniqueId);
                Log::info('[ '. $event .' ]: Entity Unique ID: ' . $entityUniqueId . ' Updated successfully in docebo');
                break;
            case 'customer.removed':
                $this->costumerRemoved($entityUniqueId);
                Log::info('[ '. $event .' ]: Entity Unique ID: ' . $entityUniqueId . ' Archived successfully in docebo');
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
        if($doceboUserData){
            $doceboConnector->send(new UpdateUserFiledsData($doceboUserData, $noviUserData['details']));
        }
    }

    public function costumerRemoved($entityUniqueId){
        $noviConnector = new NoviConnector;
        $doceboConnector =  new DoceboConnector;

        $memberDataResponse = $noviConnector->send( new GetMemberDetailFromNovi($entityUniqueId));
        $noviUserData = $memberDataResponse->dto();

        $doceboUserDataResponse = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserData['email']));
        $doceboUserData = $doceboUserDataResponse->dto();
        if($doceboUserData){
            $doceboConnector->send(new UpdateUserStatusFromDocebo($doceboUserData));
        }
    }
}
