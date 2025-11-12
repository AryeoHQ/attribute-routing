<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithGetAttributeOnClass;

use Support\Routing\Attributes\Get;
use Support\Routing\Attributes\Middleware;

#[Middleware(['auth'])]
#[Get(uri: 'invokable-resource', name: 'invokable.resource.index')]
class Controller
{
    public function __invoke() {}
}
