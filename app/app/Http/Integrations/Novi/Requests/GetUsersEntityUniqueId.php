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
            'email' => $this->email
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $item = $response->json('Results');

        $details = null;
        if(!empty($item[0])){
            $details = [
                'unique_id' => $item[0]['UniqueID'],
                'name' => $item[0]['Name']
            ];
        }
        return $details;
    }

}
