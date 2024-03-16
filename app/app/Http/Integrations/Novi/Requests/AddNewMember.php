<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AddNewMember extends Request implements HasBody
{
    use HasJsonBody;
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;
    protected Array $data;
    public function __construct(
       Array $data
    )
    {
        $this->data = $data;
    }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/api/members';
    }


    protected function defaultBody(): array
    {
        return $this->data;
    }


}
