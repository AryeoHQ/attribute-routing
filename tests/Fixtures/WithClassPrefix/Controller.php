<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithClassPrefix;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Prefix;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

#[Prefix('api')]
class Controller
{
    #[Middleware('auth')]
    #[Route(
        name: 'with-class-prefix',
        uri: 'resource',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
