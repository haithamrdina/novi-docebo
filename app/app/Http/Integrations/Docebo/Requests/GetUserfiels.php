<?php

namespace App\Http\Integrations\Docebo\Requests;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUserfiels extends Request implements Paginatable
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
        return '/manage/v1/user_fields?page_size=200';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $items = $response->json('data.items');
        $result =  [];
        foreach($items as $item){
            $i = $item['id'];
            $result[$i] =  $item['title'];
        }
        return $result;
    }
}
