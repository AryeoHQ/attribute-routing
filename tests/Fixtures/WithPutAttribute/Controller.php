<?php

declare(strict_types=1);

namespace Tests\Fixtures\WithPutAttribute;

use Support\Routing\Attributes\Middleware;
use Support\Routing\Attributes\Put;

class Controller
{
    #[Middleware(['auth'])]
    #[Put(uri: 'resource/{id}', name: 'resource.update')]
    public function __invoke() {}
}
