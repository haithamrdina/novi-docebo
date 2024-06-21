<?php

namespace App\Http\Integrations\Docebo\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUsersData extends Request implements Paginatable
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $email,
    ) {
    }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/manage/v1/user';
    }

    protected function defaultQuery(): array
    {
        return [
            'match_type' => 'full',
            'search_text' => $this->email
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $item = $response->json('data.items');
        $key = array_search('AGD Number', config('userfields'));
        if (!empty($item)) {
            $agdNumber = $item[0]['field_' . $key];
            $data = [
                'Name' => $item[0]['fullname'],
                "FirstName" => $item[0]['first_name'],
                "LastName" => $item[0]['last_name'],
                "Active" => true,
                "Email" => $item[0]['email'],
                "AccountEmail" => $item[0]['email'],
                "Approved" => true,
                "CustomerType" => "Person",
                "CustomFields" => [
                    "User created by" => [
                        "CustomerUniqueID" => "7a013cd3-a30a-4507-a887-5da26527f285",
                        "Value" => "docebo",
                        "IsSumOfChildren" => false
                    ],
                    "AGD Number" => [
                        "CustomerUniqueID" => "c0857a52-342b-47f9-815a-f6efe4d90edc",
                        "Value" => $agdNumber,
                        "IsSumOfChildren" => false
                    ]
                ]
            ];
        }
        return $data;
    }
}