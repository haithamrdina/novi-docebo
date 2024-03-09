<?php

namespace App\Http\Integrations\Docebo\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateUserStatusFromDocebo extends Request implements HasBody
{
    use HasJsonBody;
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;
    public function __construct(
        protected string $userId
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/manage/v1/user/change_status';
    }

    protected function defaultBody(): array
    {
        return [
            'user_ids' => [$this->userId],
            'status' => '0'
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $response = $response->json('data.success');
        return $response;
    }
}
