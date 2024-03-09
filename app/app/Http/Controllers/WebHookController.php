<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
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
   public function webhookNoviUpdateHandler(Request $request){
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
            case 'customer.created':

                break;
            case 'customer.updated':
                $this->costumerUpdated($entityUniqueId);
                Log::info('Entity Unique ID: ' . $entityUniqueId . ' Updated successfully in doecebo');
                break;
            case 'customer.removed':

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
        $noviUserdata = $memberDataResponse->dto();

        $doceboUserData = $doceboConnector->send(new GetUsersDataFromDocebo($noviUserdata['email']));

        if($doceboUserData){
            $doceboConnector->send(new UpdateUserFiledsData($doceboUserData['user_id'], $noviUserdata['details']));
        }
   }

   public function costumerCreated($entityUniqueId){

   }

   public function costumerRemoved($entityUniqueId){

   }
}
