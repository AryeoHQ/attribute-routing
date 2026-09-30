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

    /**
     * @param  array{path: string, middlewareGroup: ?string, prefix: ?string, domain: ?string}  $properties
     */
    public static function __set_state(array $properties): self
    {
        return new self(
            path: $properties['path'],
            middlewareGroup: $properties['middlewareGroup'],
            prefix: $properties['prefix'],
            domain: $properties['domain'],
        );
    }
}
