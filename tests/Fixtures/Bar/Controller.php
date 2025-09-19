<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bar;

use Support\Routing\Attributes\Route;
use Support\Routing\Attributes\Middleware;

class Controller
{
    #[Route(
        name: 'bar',
        uri: 'bar',
        methods: 'GET',
    )]
    #[Middleware([
        'auth',
        'throttle:100,1',
    ])]
    public function __invoke() {}
}
