<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBillingAndShippingAddress extends Request implements HasBody
{
    use HasJsonBody;
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;
    public function __construct(
        protected string $uniqueId,
        protected Array $addressData,
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/api/members/'. $this->uniqueId;
    }

    protected function defaultBody(): array
    {
        return  $this->addressData;
    }
}
