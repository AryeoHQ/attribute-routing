<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bar\Show;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'bar.show',
        uri: 'bar/{bar}',
        methods: Method::Get,
    )]
    #[Middleware([
        'auth',
        'throttle:100,1',
    ])]
    public function __invoke() {}
}
