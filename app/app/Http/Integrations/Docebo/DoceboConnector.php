<?php

namespace App\Http\Integrations\Docebo;

use App\Http\Integrations\Docebo\Auth\DoceboAuth;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Traits\Plugins\AcceptsJson;

class DoceboConnector extends Connector
{
    use AcceptsJson;

    public ?int $tries = 3;

    public ?int $retryInterval = 500;

    public ?bool $useExponentialBackoff = true;


    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('docebo.endpoint');
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'content-type' => 'application/json'
        ];
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

    /**
     * Default HTTP client authentication
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new DoceboAuth;
    }

    public function paginate(Request $request): PagedPaginator
    {
        return new class($this, $request) extends PagedPaginator
        {

            protected ?int $perPageLimit = 200;
            protected function isLastPage(Response $response): bool
            {
                return !($response->json('data.has_more_data'));
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json('data');
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->add('page', $this->currentPage + 1);

                if (isset($this->perPageLimit)) {
                    $request->query()->add('page_size', $this->perPageLimit);
                }
                return $request;
            }
        };
    }
}
