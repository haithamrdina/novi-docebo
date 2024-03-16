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
    ) { }
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
        if(!empty($item)){
            $data = [
                'Name' => $item[0]['fullname'],
                "FirstName" => $item[0]['first_name'],
                "LastName" => $item[0]['last_name'],
                "Active" => true,
                "Email" => $item[0]['email'],
                "Approved" => true,
                "CustomerType" => "Person",
            ];
        }
        return $data;
    }
}
