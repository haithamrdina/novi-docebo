<?php

namespace App\Http\Integrations\Novi;

use App\Http\Integrations\Novi\Auth\NoviAuth;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\OffsetPaginator;
use Saloon\Traits\Plugins\AcceptsJson;

class NoviConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('novi.endpoint');
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [
            'timeout' => 60,
        ];
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator('Basic '.config('novi.apikey'), 'Authorization');
    }

    public function paginate(Request $request): OffsetPaginator
    {
        return new class(connector: $this, request: $request) extends OffsetPaginator
        {
            protected ?int $perPageLimit = 1000;

            protected function isLastPage(Response $response): bool
            {
                return $this->getOffset() >= (int)$response->json('TotalCount');
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json('Results');
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->merge([
                    'pageSize' => $this->perPageLimit,
                    'offset' => $this->getOffset(),
                ]);

                return $request;
            }
        };
    }
}
