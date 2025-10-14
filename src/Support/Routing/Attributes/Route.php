<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Illuminate\Support\Arr;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Enums\Method;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Route implements RoutingAttribute
{
    /**
     * @param  array<Method>  $methods
     */
    public function __construct(
        public string $name,
        public string $uri,
        public Method|array $methods,
        public null|string $prefix = null,
        public null|bool $withTrashed = false,
    ) {}

    /**
     * @return array<Method>
     */
    public function getMethods(): array
    {
        /** @var array<Method> */
        $methods = Arr::wrap($this->methods);

        /** @var array<Method> */
        return collect($methods)
            ->toArray();
    }
}
