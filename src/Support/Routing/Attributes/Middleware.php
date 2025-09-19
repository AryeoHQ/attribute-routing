<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Illuminate\Support\Arr;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Middleware implements RoutingAttribute
{
    public function __construct(
        public string|array $middleware = [])
    {
        $this->middleware = Arr::wrap($middleware);
    }
}
