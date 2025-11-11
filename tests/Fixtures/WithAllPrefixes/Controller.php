<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithAllPrefixes;

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
        name: 'with-all-prefixes',
        uri: 'resource',
        methods: Method::Get,
        prefix: 'admin',
    )]
    public function __invoke() {}
}
