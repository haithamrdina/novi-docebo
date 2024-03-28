<?php

namespace App\Http\Integrations\Docebo\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetUserDataByUserId extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $userId,
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/manage/v1/user/'. $this->userId;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $item = $response->json('data.user_data.username');
        $data = null;
        if(!empty($item)){
            $data = $item;
        }
        return $data;
    }
}
