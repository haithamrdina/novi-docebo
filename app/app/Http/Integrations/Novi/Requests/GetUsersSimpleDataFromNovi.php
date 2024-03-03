<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetUsersSimpleDataFromNovi extends Request
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
        return '/api/members/simple-list';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $items = $response->json();
        $filteredItems = array_map(function ($item){
            $dto = [
                'noviUuid' => $item['UniqueID'],
                'fullname' => $item['Name'],
            ];
            return $dto;
        }, $items);

        return $filteredItems;
    }
}
