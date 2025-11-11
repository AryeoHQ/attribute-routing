<?php

declare(strict_types=1);

namespace Support\Routing\Attributes;

use Attribute;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class Prefix implements RoutingAttribute
{
    public function __construct(
        public string $prefix
    ) {}
}
