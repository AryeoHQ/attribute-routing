<?php

declare(strict_types=1);

namespace Support\Routing;

final readonly class DirectoryConfig
{
    public string $path;

    public ?string $middlewareGroup;

    public ?string $prefix;

    public ?string $domain;

    public function __construct(
        string $path,
        ?string $middlewareGroup = null,
        ?string $prefix = null,
        ?string $domain = null,
    ) {
        $this->path = $path;
        $this->middlewareGroup = $middlewareGroup;
        $this->prefix = $prefix;
        $this->domain = $domain;
    }
}
