<?php

declare(strict_types=1);

namespace Tests\Fixtures\Foo;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Route;

class Controller
{
    #[Route(
        name: 'foo',
        uri: 'foo',
        prefix: 'v1',
        methods: ['PUT', 'PATCH'],
        withTrashed: true,
    )]
    #[Middleware('auth')]
    public function __invoke() {}
}
