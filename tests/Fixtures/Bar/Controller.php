<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bar;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'bar',
        uri: 'bar',
        methods: Method::Get,
    )]
    #[Middleware([
        'auth',
        'throttle:100,1',
    ])]
    public function __invoke() {}
}
