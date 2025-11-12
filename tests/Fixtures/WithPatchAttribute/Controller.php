<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithPatchAttribute;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Patch;

class Controller
{
    #[Middleware(['auth'])]
    #[Patch(uri: 'resource/{id}', name: 'resource.patch')]
    public function __invoke() {}
}
