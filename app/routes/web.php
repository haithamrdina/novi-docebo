<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\WebhookDoceboController;
use App\Http\Controllers\WebHookNoviController;
use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetTransactionAddress;
use App\Http\Integrations\Docebo\Requests\GetUserDataByUserId;
use App\Http\Integrations\Docebo\Requests\GetUserfiels;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersEntityUniqueId;
use App\Http\Integrations\Novi\Requests\UpdateBillingAndShippingAddress;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('transaction', function () {
    $doceboConnector = new DoceboConnector;
    $noviConnector = new NoviConnector;
    $transaction_id = 20;
    $doceboId = 22346;
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
});

/** webhooks @s */
//Route::post('novi-listener', [WebHookController::class, 'webhookNoviHandler']);
Route::post('novi-update-listener', [WebHookNoviController::class, 'noviUpdateHandle']);
Route::post('novi-remove-listener', [WebHookNoviController::class, 'noviRemoveHandle']);
Route::post('docebo-listener', [WebHookController::class, 'webhookDoceboHandler']);
Route::post('docebo-create-listener', [WebhookDoceboController::class, 'doceboCreateHandle']);
Route::post('docebo-transaction-listener', [WebhookDoceboController::class, 'doceboTransactionHandle']);
Route::post('docebo-selfregistred-listener', [WebhookDoceboController::class, 'doceboSelfregistredHandle']);
/** webhooks @e */

/** app @s */
require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {

    /*Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/verify', [HomeController::class, 'verify'])->name('home.verify');
    Route::get('/home/sync', [HomeController::class, 'sync'])->name('home.sync');
    Route::get('/home/empty', [HomeController::class, 'empty'])->name('home.empty');*/
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('userfields', [ConfigController::class, 'index'])->name('index');
        Route::post('userfields', [ConfigController::class, 'update'])->name('update');
        Route::get('docebo', [ConfigController::class, 'docebo'])->name('docebo.index');
        Route::post('docebo', [ConfigController::class, 'doceboupdate'])->name('docebo.update');
        Route::get('novi', [ConfigController::class, 'novi'])->name('novi.index');
        Route::post('novi', [ConfigController::class, 'noviupdate'])->name('novi.update');
    });
});
/** app @e */