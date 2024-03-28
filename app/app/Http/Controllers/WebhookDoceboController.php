<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetTransactionAddress;
use App\Http\Integrations\Docebo\Requests\GetUserDataByUserId;
use App\Http\Integrations\Docebo\Requests\GetUsersData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\AddNewMember;
use App\Http\Integrations\Novi\Requests\GetUsersEntityUniqueId;
use App\Http\Integrations\Novi\Requests\UpdateBillingAndShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookDoceboController extends Controller
{
    /**
    * @param Request $request
    * @return json
    */
    public function doceboCreateHandle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $username = $payload['payload']['username'] ?? null;
        $doceboId = $payload['payload']['user_id'] ?? null;

        if (!$username || !$doceboId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        $doceboConnector =  new DoceboConnector;
        $noviConnector = new NoviConnector;
        $doceboUsersDataResponse = $doceboConnector->send(new GetUsersData($username));
        $doceboUserData = $doceboUsersDataResponse->dto();
        if($doceboUserData){
            $noviConnector->send(new AddNewMember($doceboUserData));
            Log::info('["DOCEBO LMS"][user.created]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Added successfully in Novi');
            return response()->json(['status' => 'success'] , 200);
        }else{
            Log::warning('["DOCEBO LMS"][user.created]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Unexpected error');
            return response()->json(['status' => 'success'] , 200);
        }

    }


    /**
    * @param Request $request
    * @return json
    */
    public function doceboTransactionHandle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $transaction_id = $payload['payload']['transaction_id'] ?? null;
        $doceboId = $payload['payload']['user_id'] ?? null;

        if (!$transaction_id || !$doceboId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        $doceboConnector =  new DoceboConnector;
        $noviConnector = new NoviConnector;

        $transactionAddressResponse = $doceboConnector->send(new GetTransactionAddress($transaction_id));
        $transactionAddressData = $transactionAddressResponse->dto();

        $doceboUsernameResponse = $doceboConnector->send(new GetUserDataByUserId($doceboId));
        $doceboUsernameData = $doceboUsernameResponse->dto();

        if(!empty($transactionAddressData) && !empty($doceboUsernameData)){

           $noviUserUniqueIdResponse = $noviConnector->send(new GetUsersEntityUniqueId($doceboUsernameData));
           $noviUserUniqueIdData = $noviUserUniqueIdResponse->dto();
           if($noviUserUniqueIdData){
                $addressData = [
                    "Name" => $noviUserUniqueIdData['name'],
                    "CustomerType" => "Person",
                    "BillingAddress" => [
                        "Address1" => $transactionAddressData['Address1'],
                        "Address2" =>  $transactionAddressData['Address2'],
                        "City" => $transactionAddressData['City'],
                        "ZipCode" =>  $transactionAddressData['ZipCode'],
                        "StateProvince" =>  $transactionAddressData['StateProvince'],
                        "Country" =>  $transactionAddressData['Country']
                    ],
                    "BillingAddress" => [
                        "Address1" => $transactionAddressData['Address1'],
                        "Address2" =>  $transactionAddressData['Address2'],
                        "City" => $transactionAddressData['City'],
                        "ZipCode" =>  $transactionAddressData['ZipCode'],
                        "StateProvince" =>  $transactionAddressData['StateProvince'],
                        "Country" =>  $transactionAddressData['Country']
                    ]
                ];
                $noviConnector->send(new UpdateBillingAndShippingAddress($noviUserUniqueIdData['unique_id'], $addressData));
                Log::info('["DOCEBO LMS"][ecommerce.transaction.created]: Entity DOCEBO Unique ID: ' . $doceboId . ' and Transaction Unique ID : ' . $transaction_id . '  Updated successffully on NOVI');
                return response()->json(['status' => 'success'] , 200);
           }else{
                Log::warning('["DOCEBO LMS"][ecommerce.transaction.created][NOVI AMS]: Entity DOCEBO Unique ID: ' . $doceboId . 'The Email not found on NOVI AMS');
                return response()->json(['status' => 'success'] , 200);
           }

        }else{
            Log::warning('["DOCEBO LMS"][ecommerce.transaction.created]: Entity DOCEBO Unique ID: ' . $doceboId . ' and Transaction Unique ID : ' . $transaction_id . '  Unexpected error');
            return response()->json(['status' => 'success'] , 200);
        }
    }
}
