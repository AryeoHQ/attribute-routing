<?php

declare(strict_types=1);

namespace Tests\Fixtures\Prefixed\Resources\Show;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class PrefixedController
{
    #[Route(
        name: 'prefixed.resources.show.route-prefix',
        uri: 'resources/{resource}',
        prefix: 'v2',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
