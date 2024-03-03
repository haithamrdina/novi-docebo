<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMemberCustomFiels extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/api/custom-fields';
    }

    protected function defaultQuery(): array
    {
        return [
            'customFieldType' => 'members'
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $items = $response->json();
        $result = array_map(function ($item) {
            return   $item['Name'];
        }, $items);

        return $result;
    }
}
