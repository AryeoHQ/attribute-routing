<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bar;

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
        name: 'bar',
        uri: 'bar',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
