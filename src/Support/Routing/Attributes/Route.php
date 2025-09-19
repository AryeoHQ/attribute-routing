<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Illuminate\Routing\Router;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Route implements RoutingAttribute
{
    public function __construct(
        public string $name,
        public string $uri,
        public array|string $methods,
        public ?string $prefix = null,
        public ?bool $withTrashed = false,
    ) {}

    public function getMethods(): array
    {
        return collect($this->methods)
            ->map(fn (string $method) => strtoupper($method))
            ->filter(fn (string $method) => in_array($method, Router::$verbs))
            ->toArray();
    }
}
