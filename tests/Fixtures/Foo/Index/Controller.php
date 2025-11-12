<?php

declare(strict_types=1);

namespace Tests\Fixtures\Foo\Index;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Middleware('auth')]
    #[Route(
        name: 'foo.index',
        uri: 'foo',
        prefix: 'v1',
        methods: [Method::Put, Method::Patch],
        withTrashed: true,
    )]
    public function __invoke() {}
}
