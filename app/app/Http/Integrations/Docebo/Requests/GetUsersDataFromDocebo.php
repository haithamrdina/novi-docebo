<?php

namespace App\Http\Integrations\Docebo\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUsersDataFromDocebo extends Request implements Paginatable
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
        return '/manage/v1/user';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $items = $response->json('data.items');
        $filteredItems = array_map(function ($item){
            $dto = [
                'docebo_id' => $item['user_id'],
                'fullname' => $item['first_name'].' '.$item['last_name'],
                'firstname' => $item['first_name'],
                'lastname' => $item['last_name'],
                'username' => $item['username'],
            ];
            return $dto;
        }, $items);

        return $filteredItems;
    }
}
