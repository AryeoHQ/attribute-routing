<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithBothPrefixes;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Prefix;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

#[Prefix('api')]
class Controller
{
    #[Middleware('auth')]
    #[Prefix('v1')]
    #[Route(
        name: 'with-both-prefixes',
        uri: 'resource',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
