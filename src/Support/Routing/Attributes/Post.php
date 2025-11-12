<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Enums\Method;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Post implements RoutingAttribute
{
    public function __construct(
        public string $uri,
        public ?string $name = null,
        public ?string $prefix = null,
        public ?bool $withTrashed = false,
    ) {}

    /**
     * @return array<Method>
     */
    public function getMethods(): array
    {
        return [Method::Post];
    }
}
