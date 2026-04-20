<?php

declare(strict_types=1);

namespace Support\Routing;

use Support\Routing\Enums\Method;

final readonly class RouteDetails
{
    public string $name;

    public string $uri;

    /** @var array<Method> */
    public array $methods;

    /** @var class-string<object>|array{class-string<object>, non-empty-string} */
    public string|array $action;

    public string $prefix;

    public ?bool $withTrashed;

    /** @var array<class-string> */
    public array $withoutMiddleware;

    /** @var array<string> */
    public array $middleware;

    /**
     * @param  array<Method>  $methods
     * @param  class-string<object>|array{class-string<object>, non-empty-string}  $action
     * @param  array<string>  $middleware
     * @param  array<class-string>  $withoutMiddleware
     */
    public function __construct(
        string $name,
        string $uri,
        array $methods,
        string|array $action,
        string $prefix,
        ?bool $withTrashed,
        array $withoutMiddleware,
        array $middleware,
    ) {
        $this->name = $name;
        $this->uri = $uri;
        $this->methods = $methods;
        $this->action = $action;
        $this->prefix = $prefix;
        $this->withTrashed = $withTrashed;
        $this->withoutMiddleware = $withoutMiddleware;
        $this->middleware = $middleware;
    }
}
