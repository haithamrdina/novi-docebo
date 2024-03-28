<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/novi-listener',
        '/docebo-listener',
        '/novi-update-listener',
        '/novi-remove-listener',
        'docebo-create-listener',
        'docebo-transaction-listener'
    ];
}
