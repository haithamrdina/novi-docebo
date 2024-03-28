<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUsersEntityUniqueId extends Request implements Paginatable
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $email,
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/api/members';
    }

    protected function defaultQuery(): array
    {
        return [
            'accountEmail' => $this->email
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $item = $response->json('Results');

        $details = null;
        if(!empty($item[0])){
            $details = [
                'unique_id' => $item[0]['UniqueID'],
                'Name' => $item[0]['Name'],
                "AccountEmail" => $item[0]['AccountEmail'],
                "Active" => $item[0]['Active'],
                "Approved" => $item[0]['Approved'],
                "AutoPay" => $item[0]['AutoPay'],
                "AutoRenew" => $item[0]['AutoRenew'],
                "Awards" => $item[0]['Awards'],
                "BillingAddress" => $item[0]['BillingAddress'],
                "BillingContactUniqueId" => $item[0]['BillingContactUniqueId'],
                "Committees" => $item[0]['Committees'],
                "County" => $item[0]['County'],
                "CreatedDate" => $item[0]['CreatedDate'],
                "Credentials" => $item[0]['Credentials'],
                "CustomerType" => $item[0]['CustomerType'],
                "CustomFields" => $item[0]['CustomFields'],
                "DefaultDuesPayerOverride" => $item[0]['DefaultDuesPayerOverride'],
                "DirectoryGallery" => $item[0]['DirectoryGallery'],
                "DuesPayerUniqueID" => $item[0]['DuesPayerUniqueID'],
                "Education" => $item[0]['Education'],
                "EffectiveMemberType" => $item[0]['EffectiveMemberType'],
                "Email" => $item[0]['Email'],
                "FacebookUrl" => $item[0]['FacebookUrl'],
                "Fax" => $item[0]['Fax'],
                "FirstName" => $item[0]['FirstName'],
                "Groups" => $item[0]['Groups'],
                "HideContactInformation" => $item[0]['HideContactInformation'],
                "HideOnWebsite" => $item[0]['HideOnWebsite'],
                "Image" => $item[0]['Image'],
                "InstagramHandle" => $item[0]['InstagramHandle'],
                "InternalIdentifier" => $item[0]['InternalIdentifier'],
                "IsInstructor" => $item[0]['IsInstructor'],
                "JobTitle" => $item[0]['JobTitle'],
                "LastName" => $item[0]['LastName'],
                "LastUpdatedDate" => $item[0]['LastUpdatedDate'],
                "LinkedInUrl" => $item[0]['LinkedInUrl'],
                "ManagementAccessForCompanies" => $item[0]['ManagementAccessForCompanies'],
                "MemberProfile" => $item[0]['MemberProfile'],
                "MembershipExpires" => $item[0]['MembershipExpires'],
                "MemberSince" => $item[0]['MemberSince'],
                "MemberStatus" => $item[0]['MemberStatus'],
                "MemberSubStatus" => $item[0]['MemberSubStatus'],
                "MemberType" => $item[0]['MemberType'],
                "MiddleName" => $item[0]['MiddleName'],
                "Mobile" => $item[0]['Mobile'],
                "Name" => $item[0]['Name'],
                "Notes" => $item[0]['Notes'],
                "OpenDuesBalance" => $item[0]['OpenDuesBalance'],
                "OriginalJoinDate" => $item[0]['OriginalJoinDate'],
                "ParentCustomerUniqueID" => $item[0]['ParentCustomerUniqueID'],
                "ParentMemberName" => $item[0]['ParentMemberName'],
                "PersonalAddress" => $item[0]['PersonalAddress'],
                "PersonalEmail" => $item[0]['PersonalEmail'],
                "PersonalMobile" => $item[0]['PersonalMobile'],
                "PersonalPhone" => $item[0]['PersonalPhone'],
                "Phone" => $item[0]['Phone'],
                "PrimaryContactUniqueId" => $item[0]['PrimaryContactUniqueId'],
                "QuickBooksID" => $item[0]['QuickBooksID'],
                "ShippingAddress" => $item[0]['ShippingAddress'],
                "SpecifiedSystemFields" => $item[0]['SpecifiedSystemFields'],
                "Suffix" => $item[0]['Suffix'],
                "Taxable" => $item[0]['Taxable'],
                "TaxExemptionReason" => $item[0]['TaxExemptionReason'],
                "Title" => $item[0]['Title'],
                "TwitterHandle" => $item[0]['TwitterHandle'],
                "UniqueID" => $item[0]['UniqueID'],
                "UnsubscribeFromEmails" => $item[0]['UnsubscribeFromEmails'],
                "UseParentBilling" => $item[0]['UseParentBilling'],
                "UseParentShipping" => $item[0]['UseParentShipping'],
                "VolunteerWorks" => $item[0]['VolunteerWorks'],
                "Website" => $item[0]['Website']
            ];
        }
        return $details;
    }

}
