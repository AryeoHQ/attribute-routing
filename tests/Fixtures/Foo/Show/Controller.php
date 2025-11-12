<?php

declare(strict_types=1);

namespace Tests\Fixtures\Foo\Show;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'foo.show',
        uri: 'foo/{foo}',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
