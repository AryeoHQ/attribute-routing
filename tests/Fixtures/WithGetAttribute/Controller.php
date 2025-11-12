<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithGetAttribute;

use Support\Routing\Attributes\Get;
use Support\Routing\Attributes\Middleware;

class Controller
{
    #[Middleware(['auth'])]
    #[Get(uri: 'resource', name: 'resource.index')]
    public function __invoke() {}
}
