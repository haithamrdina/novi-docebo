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
        $user_id = null;
        if(!empty($item)){
            $user_id = $item[0]['user_id'];
        }
        return $user_id;
    }
}
