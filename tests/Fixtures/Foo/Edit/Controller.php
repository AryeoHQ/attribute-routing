<?php

declare(strict_types=1);

namespace Tests\Fixtures\Foo\Edit;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;
use Tests\Fixtures\Middleware\GlobalMiddleware;

class Controller
{
    #[Route(
        name: 'foo.edit',
        uri: 'foo/{foo}/edit',
        methods: Method::Get,
        withoutMiddleware: [
            GlobalMiddleware::class,
        ],
    )]
    public function __invoke() {}
}
