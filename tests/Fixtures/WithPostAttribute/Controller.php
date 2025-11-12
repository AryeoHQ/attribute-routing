<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithPostAttribute;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Post;

class Controller
{
    #[Middleware(['auth'])]
    #[Post(uri: 'resource', name: 'resource.store')]
    public function __invoke() {}
}
