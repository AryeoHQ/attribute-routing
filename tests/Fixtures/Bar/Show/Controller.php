<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bar\Show;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Middleware([
        'auth',
        'throttle:100,1',
    ])]
    #[Route(
        name: 'bar.show',
        uri: 'bar/{bar}',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
