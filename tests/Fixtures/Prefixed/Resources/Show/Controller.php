<?php

declare(strict_types=1);

namespace Tests\Fixtures\Prefixed\Resources\Show;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'prefixed.resources.show',
        uri: 'resources/{resource}',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
