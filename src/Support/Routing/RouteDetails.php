<?php

declare(strict_types=1);

namespace Support\Routing;

use Support\Routing\Enums\Method;

final readonly class RouteDetails
{
    /**
     * @param  array<Method>  $methods
     * @param  class-string<object>|array{class-string<object>, non-empty-string}  $action
     * @param  array<string>  $middleware
     * @param  array<class-string>  $withoutMiddleware
     */
    public function __construct(
        public string $name,
        public string $uri,
        public array $methods,
        public string|array $action,
        public string $prefix,
        public ?bool $withTrashed,
        public array $withoutMiddleware,
        public array $middleware,
    ) {}
}
