<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUserfiels;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberCustomFiels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ConfigController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $doceboConnector = new DoceboConnector();
        $doceboUserfieldsResponse = $doceboConnector->send(new GetUserfiels);
        $doceboUserFields = $doceboUserfieldsResponse->dto();

        $noviConnector = new NoviConnector();
        $noviMemberFields = [
            "AccountEmail",
            "Active",
            "Approved",
            "AutoPay",
            "AutoRenew",
            "Awards",
            "BillingAddress",
            "BillingContactUniqueId",
            "Committees",
            "County",
            "CreatedDate",
            "Credentials",
            "CustomerType",
            "CustomFields",
            "DefaultDuesPayerOverride",
            "DirectoryGallery",
            "DuesPayerUniqueID",
            "Education",
            "EffectiveMemberType",
            "Email",
            "FacebookUrl",
            "Fax",
            "FirstName",
            "Groups",
            "HideContactInformation",
            "HideOnWebsite",
            "Image",
            "InstagramHandle",
            "InternalIdentifier",
            "IsInstructor",
            "JobTitle",
            "LastName",
            "LastUpdatedDate",
            "LinkedInUrl",
            "ManagementAccessForCompanies",
            "MemberProfile",
            "MembershipExpires",
            "MemberSince",
            "MemberStatus",
            "MemberSubStatus",
            "MemberType",
            "MiddleName",
            "Mobile",
            "Name",
            "Notes",
            "OpenDuesBalance",
            "OriginalJoinDate",
            "ParentCustomerUniqueID",
            "ParentMemberName",
            "PersonalAddress",
            "PersonalEmail",
            "PersonalMobile",
            "PersonalPhone",
            "Phone",
            "PrimaryContactUniqueId",
            "QuickBooksID",
            "ShippingAddress",
            "SpecifiedSystemFields",
            "Suffix",
            "Taxable",
            "TaxExemptionReason",
            "Title",
            "TwitterHandle",
            "UniqueID",
            "UnsubscribeFromEmails",
            "UseParentBilling",
            "UseParentShipping",
            "VolunteerWorks",
            "Website",
            "CustomerType",
            'UniqueID',
            'OpenDuesBalance',

        ];
        $noviMemberfieldsResponse = $noviConnector->send(new GetMemberCustomFiels);
        $noviMemberFields = array_merge($noviMemberFields , $noviMemberfieldsResponse->dto());


        return view('settings.index', compact('doceboUserFields','noviMemberFields'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $configFilePath = config_path('userfields.php');
        $configContent = '<?php' . PHP_EOL . 'return [' . PHP_EOL;
        foreach($data as $key => $value){
            if($value=='Exam'){
                $configContent .= "    '$key' => '$value '," . PHP_EOL;
            }else{
                $configContent .= "    '$key' => '$value'," . PHP_EOL;
            }
        }
        $configContent .= '];' . PHP_EOL;
        File::put($configFilePath, $configContent);
        Artisan::call('config:clear');
        return redirect()->route('settings.index')->with('success', 'Configuration updated successfully!');
    }

    public function docebo(){
        return view('settings.docebo.index');
    }

    public function doceboupdate(Request $request)
    {
        $data = [
            'endpoint' => '"'.$request->input('docebo-endpoint').'"',
            'username' => '"'.$request->input('docebo-username').'"',
            'password' => '"'.$request->input('docebo-password').'"',
        ];
        $configFilePath = config_path('docebo.php');
        $configContent = '<?php' . PHP_EOL . 'return [' . PHP_EOL;

        foreach ($data as $key => $value) {
            $configContent .= "    '$key' => $value," . PHP_EOL;
        }
        $configContent .= '];' . PHP_EOL;
        File::put($configFilePath, $configContent);
        Artisan::call('config:clear');
        return redirect()->route('settings.docebo.index')->with('success', 'Configuration updated successfully!');
    }

    public function novi(){
        return view('settings.novi.index');
    }

    public function noviupdate(Request $request)
    {
        $data = [
            'endpoint' => '"'.$request->input('novi-endpoint').'"',
            'apikey' => '"'.$request->input('novi-password').'"',
        ];
        $configFilePath = config_path('novi.php');
        $configContent = '<?php' . PHP_EOL . 'return [' . PHP_EOL;

        foreach ($data as $key => $value) {
            $configContent .= "    '$key' => $value," . PHP_EOL;
        }
        $configContent .= '];' . PHP_EOL;
        File::put($configFilePath, $configContent);
        Artisan::call('config:clear');
        return redirect()->route('settings.novi.index')->with('success', 'Configuration updated successfully!');
    }
}
