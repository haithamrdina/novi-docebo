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
        $item = $response->json('data.date_completed');
        $data = null;
        if(!empty($item)){
            $data = [
                "BillingAddress" => [
                    "Address1" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['address'],
                    "Address2" => null,
                    "City" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['city'],
                    "ZipCode" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['zip'],
                    "StateProvince" => null,
                    "Country" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['country']
                ],
                "ShippingAddress" => [
                    "Address1" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['address'],
                    "Address2" => null,
                    "City" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['city'],
                    "ZipCode" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['zip'],
                    "StateProvince" => null,
                    "Country" => $item[0]['buyer_data']['enrolled_user']['gateway_information']['billing_data']['country']
                ],
                "CustomerType" => "Person",
            ];
        }
        return $data;
    }
}
