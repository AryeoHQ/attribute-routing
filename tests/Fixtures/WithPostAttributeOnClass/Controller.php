<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithPostAttributeOnClass;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Post;

#[Middleware(['auth'])]
#[Post(uri: 'invokable-resource', name: 'invokable.resource.store')]
class Controller
{
    public function __invoke() {}
}
