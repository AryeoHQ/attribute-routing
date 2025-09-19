<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Illuminate\Support\Arr;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Middleware implements RoutingAttribute
{
    /**
     * @param  array<string>  $middleware
     */
    public function __construct(
        public string|array $middleware) {}

    /**
     * @return array<string>
     */
    public function getMiddleware(): array
    {
        /** @var array<string> */
        return Arr::wrap($this->middleware);
    }
}
