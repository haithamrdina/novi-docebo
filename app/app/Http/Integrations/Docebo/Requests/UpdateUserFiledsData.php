<?php

namespace App\Http\Integrations\Docebo\Requests;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateUserFiledsData extends Request implements HasBody
{
    use HasJsonBody;
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;

    public function __construct(
        protected string $userId,
        protected Array $data
    ) { }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/manage/v1/user/' .$this->userId;
    }

    protected function defaultBody(): array
    {
        return [
            'additional_fields' => $this->data,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $response = $response->json('data.success');
        return $response;
    }
}
