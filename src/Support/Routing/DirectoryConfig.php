<?php

declare(strict_types=1);

namespace Support\Routing;

final readonly class DirectoryConfig
{
    public function __construct(
        public string $path,
        public ?string $middlewareGroup = null,
        public ?string $prefix = null,
        public ?string $domain = null,
    ) {}
}
