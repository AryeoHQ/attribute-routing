<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithDeleteAttribute;

use Support\Routing\Attributes\Delete;
use Support\Routing\Attributes\Middleware;

class Controller
{
    #[Middleware(['auth'])]
    #[Delete(uri: 'resource/{id}', name: 'resource.destroy')]
    public function __invoke() {}
}
