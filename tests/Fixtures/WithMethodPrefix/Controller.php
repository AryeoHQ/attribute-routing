<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithMethodPrefix;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Prefix;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Middleware('auth')]
    #[Prefix('api')]
    #[Route(
        name: 'with-method-prefix',
        uri: 'resource',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
