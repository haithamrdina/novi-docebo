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

        $doceboConnector = new DoceboConnector;
        $noviConnector = new NoviConnector;
        $doceboUsersDataResponse = $doceboConnector->send(new GetUsersData($username));
        $doceboUserData = $doceboUsersDataResponse->dto();
        if ($doceboUserData) {
            $noviConnector->send(new AddNewMember($doceboUserData));
            Log::info('["DOCEBO LMS"][user.created]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Added successfully in Novi');
            return response()->json(['status' => 'success'], 200);
        } else {
            Log::warning('["DOCEBO LMS"][user.created]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Unexpected error');
            return response()->json(['status' => 'success'], 200);
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

        $doceboConnector = new DoceboConnector;
        $noviConnector = new NoviConnector;

        $transactionAddressResponse = $doceboConnector->send(new GetTransactionAddress($transaction_id));
        $transactionAddressData = $transactionAddressResponse->dto();

        $doceboUsernameResponse = $doceboConnector->send(new GetUserDataByUserId($doceboId));
        $doceboUsernameData = $doceboUsernameResponse->dto();

        if (!empty($transactionAddressData) && !empty($doceboUsernameData)) {

            $noviUserUniqueIdResponse = $noviConnector->send(new GetUsersEntityUniqueId($doceboUsernameData));
            $noviUserUniqueIdData = $noviUserUniqueIdResponse->dto();
            if ($noviUserUniqueIdData) {
                $addressData = [
                    'Name' => $noviUserUniqueIdData['Name'],
                    "AccountEmail" => $noviUserUniqueIdData['AccountEmail'],
                    "Active" => $noviUserUniqueIdData['Active'],
                    "Approved" => $noviUserUniqueIdData['Approved'],
                    "AutoPay" => $noviUserUniqueIdData['AutoPay'],
                    "AutoRenew" => $noviUserUniqueIdData['AutoRenew'],
                    "Awards" => $noviUserUniqueIdData['Awards'],
                    "BillingAddress" => $noviUserUniqueIdData['BillingAddress'],
                    "BillingContactUniqueId" => $noviUserUniqueIdData['BillingContactUniqueId'],
                    "Committees" => $noviUserUniqueIdData['Committees'],
                    "County" => $noviUserUniqueIdData['County'],
                    "CreatedDate" => $noviUserUniqueIdData['CreatedDate'],
                    "Credentials" => $noviUserUniqueIdData['Credentials'],
                    "CustomerType" => $noviUserUniqueIdData['CustomerType'],
                    "CustomFields" => $noviUserUniqueIdData['CustomFields'],
                    "DefaultDuesPayerOverride" => $noviUserUniqueIdData['DefaultDuesPayerOverride'],
                    "DirectoryGallery" => $noviUserUniqueIdData['DirectoryGallery'],
                    "DuesPayerUniqueID" => $noviUserUniqueIdData['DuesPayerUniqueID'],
                    "Education" => $noviUserUniqueIdData['Education'],
                    "EffectiveMemberType" => $noviUserUniqueIdData['EffectiveMemberType'],
                    "Email" => $noviUserUniqueIdData['Email'],
                    "FacebookUrl" => $noviUserUniqueIdData['FacebookUrl'],
                    "Fax" => $noviUserUniqueIdData['Fax'],
                    "FirstName" => $noviUserUniqueIdData['FirstName'],
                    "Groups" => $noviUserUniqueIdData['Groups'],
                    "HideContactInformation" => $noviUserUniqueIdData['HideContactInformation'],
                    "HideOnWebsite" => $noviUserUniqueIdData['HideOnWebsite'],
                    "Image" => $noviUserUniqueIdData['Image'],
                    "InstagramHandle" => $noviUserUniqueIdData['InstagramHandle'],
                    "InternalIdentifier" => $noviUserUniqueIdData['InternalIdentifier'],
                    "IsInstructor" => $noviUserUniqueIdData['IsInstructor'],
                    "JobTitle" => $noviUserUniqueIdData['JobTitle'],
                    "LastName" => $noviUserUniqueIdData['LastName'],
                    "LastUpdatedDate" => $noviUserUniqueIdData['LastUpdatedDate'],
                    "LinkedInUrl" => $noviUserUniqueIdData['LinkedInUrl'],
                    "ManagementAccessForCompanies" => $noviUserUniqueIdData['ManagementAccessForCompanies'],
                    "MemberProfile" => $noviUserUniqueIdData['MemberProfile'],
                    "MembershipExpires" => $noviUserUniqueIdData['MembershipExpires'],
                    "MemberSince" => $noviUserUniqueIdData['MemberSince'],
                    "MemberStatus" => $noviUserUniqueIdData['MemberStatus'],
                    "MemberSubStatus" => $noviUserUniqueIdData['MemberSubStatus'],
                    "MemberType" => $noviUserUniqueIdData['MemberType'],
                    "MiddleName" => $noviUserUniqueIdData['MiddleName'],
                    "Mobile" => $noviUserUniqueIdData['Mobile'],
                    "Name" => $noviUserUniqueIdData['Name'],
                    "Notes" => $noviUserUniqueIdData['Notes'],
                    "OpenDuesBalance" => $noviUserUniqueIdData['OpenDuesBalance'],
                    "OriginalJoinDate" => $noviUserUniqueIdData['OriginalJoinDate'],
                    "ParentCustomerUniqueID" => $noviUserUniqueIdData['ParentCustomerUniqueID'],
                    "ParentMemberName" => $noviUserUniqueIdData['ParentMemberName'],
                    "PersonalAddress" => $noviUserUniqueIdData['PersonalAddress'],
                    "PersonalEmail" => $noviUserUniqueIdData['PersonalEmail'],
                    "PersonalMobile" => $noviUserUniqueIdData['PersonalMobile'],
                    "PersonalPhone" => $noviUserUniqueIdData['PersonalPhone'],
                    "Phone" => $noviUserUniqueIdData['Phone'],
                    "PrimaryContactUniqueId" => $noviUserUniqueIdData['PrimaryContactUniqueId'],
                    "QuickBooksID" => $noviUserUniqueIdData['QuickBooksID'],
                    "ShippingAddress" => $noviUserUniqueIdData['ShippingAddress'],
                    "SpecifiedSystemFields" => $noviUserUniqueIdData['SpecifiedSystemFields'],
                    "Suffix" => $noviUserUniqueIdData['Suffix'],
                    "Taxable" => $noviUserUniqueIdData['Taxable'],
                    "TaxExemptionReason" => $noviUserUniqueIdData['TaxExemptionReason'],
                    "Title" => $noviUserUniqueIdData['Title'],
                    "TwitterHandle" => $noviUserUniqueIdData['TwitterHandle'],
                    "UniqueID" => $noviUserUniqueIdData['UniqueID'],
                    "UnsubscribeFromEmails" => $noviUserUniqueIdData['UnsubscribeFromEmails'],
                    "UseParentBilling" => $noviUserUniqueIdData['UseParentBilling'],
                    "UseParentShipping" => $noviUserUniqueIdData['UseParentShipping'],
                    "VolunteerWorks" => $noviUserUniqueIdData['VolunteerWorks'],
                    "Website" => $noviUserUniqueIdData['Website'],
                    "BillingAddress" => [
                        "Address1" => $transactionAddressData['Address1'],
                        "Address2" => $transactionAddressData['Address2'],
                        "City" => $transactionAddressData['City'],
                        "ZipCode" => $transactionAddressData['ZipCode'],
                        "StateProvince" => $transactionAddressData['StateProvince'],
                        "Country" => $transactionAddressData['Country']
                    ],
                    "ShippingAddress" => [
                        "Address1" => $transactionAddressData['Address1'],
                        "Address2" => $transactionAddressData['Address2'],
                        "City" => $transactionAddressData['City'],
                        "ZipCode" => $transactionAddressData['ZipCode'],
                        "StateProvince" => $transactionAddressData['StateProvince'],
                        "Country" => $transactionAddressData['Country']
                    ]
                ];
                $noviConnector->send(new UpdateBillingAndShippingAddress($noviUserUniqueIdData['unique_id'], $addressData));
                Log::info('["DOCEBO LMS"][ecommerce.transaction.created]: Entity DOCEBO Unique ID: ' . $doceboId . ' and Transaction Unique ID : ' . $transaction_id . '  Updated successffully on NOVI');
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::warning('["DOCEBO LMS"][ecommerce.transaction.created][NOVI AMS]: Entity DOCEBO Unique ID: ' . $doceboId . 'The Email not found on NOVI AMS');
                return response()->json(['status' => 'success'], 200);
            }

        } else {
            Log::warning('["DOCEBO LMS"][ecommerce.transaction.created]: Entity DOCEBO Unique ID: ' . $doceboId . ' and Transaction Unique ID : ' . $transaction_id . '  Unexpected error');
            return response()->json(['status' => 'success'], 200);
        }
    }

    /**
     * @param Request $request
     * @return json
     */
    public function doceboSelfregistredHandle(Request $request)
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

        $doceboConnector = new DoceboConnector;
        $noviConnector = new NoviConnector;
        $doceboUsersDataResponse = $doceboConnector->send(new GetUsersData($username));
        $doceboUserData = $doceboUsersDataResponse->dto();
        if ($doceboUserData) {
            $noviConnector->send(new AddNewMember($doceboUserData));
            Log::info('["DOCEBO LMS"][user.selfregistred]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Added successfully in Novi');
            return response()->json(['status' => 'success'], 200);
        } else {
            Log::warning('["DOCEBO LMS"][user.selfregistred]: Entity DOCEBO Unique ID: : ' . $doceboId . ' Unexpected error');
            return response()->json(['status' => 'success'], 200);
        }

    }
}
