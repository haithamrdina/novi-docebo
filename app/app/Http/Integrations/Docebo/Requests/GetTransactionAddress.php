<?php

namespace App\Http\Integrations\Docebo\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTransactionAddress extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $transactionId,
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/ecommerce/v1/transaction/get';
    }

    protected function defaultQuery(): array
    {
        return [
            'transaction_id' => $this->transactionId
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $item = $response->json('data.billing_data');
        $data = null;
        if(!empty($item)){
            $data = [
                "BillingAddress" => [
                    "Address1" => $item['address'],
                    "Address2" => null,
                    "City" => $item['city'],
                    "ZipCode" => $item['zip'],
                    "StateProvince" => $item['state'],
                    "Country" => $item['country']
                ],
                "ShippingAddress" => [
                    "Address1" => $item['address'],
                    "Address2" => null,
                    "City" => $item['city'],
                    "ZipCode" => $item['zip'],
                    "StateProvince" => $item['state'],
                    "Country" => $item['country']
                ],
                "CustomerType" => "Person",
            ];
        }
        return $data;
    }
}
