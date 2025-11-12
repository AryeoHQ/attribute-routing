<?php

namespace Tests;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Orchestra\Testbench;
use Support\Routing\Enums\Method;
use Support\Routing\RouteRegistrar;
use Tests\Fixtures\Middleware\GlobalMiddleware;

abstract class TestCase extends Testbench\TestCase
{
    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;

    protected RouteRegistrar $routeRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routeRegistrar = app(RouteRegistrar::class);
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        parent::resolveApplicationHttpKernel($app);

        $app[Kernel::class]->pushMiddleware(GlobalMiddleware::class);
    }

    public function getFixture(string $fixture): string
    {
        return __DIR__.'/Fixtures/'.$fixture;
    }

    public function assertRouteRegistered(
        string $controller,
        string $name,
        string $uri,
        Method $httpMethod,
        string|array|null $middleware,
        null|bool $withTrashed = false,
        null|array $withoutMiddleware = [],
    ): self {
        $routes = collect(app()->router->getRoutes());

        $routeRegistered = $routes
            ->contains(function (Route $route) use ($controller, $name, $uri, $httpMethod, $middleware, $withTrashed, $withoutMiddleware) {
                $routeController = $route->getAction(0) ?? $route->getController() !== null
                    ? get_class($route->getController())
                    : null;

                if ($routeController !== $controller) {
                    return false;
                }

                if ($route->getName() !== $name) {
                    return false;
                }

                if ($route->uri() !== $uri) {
                    return false;
                }

                if (! in_array($httpMethod->value, $route->methods)) {
                    return false;
                }

                if (array_diff(Arr::wrap($middleware), $route->middleware())) {
                    return false;
                }

                if (array_diff(Arr::wrap($withoutMiddleware), $route->excludedMiddleware())) {
                    return false;
                }

                if ($route->allowsTrashedBindings() !== $withTrashed) {
                    return false;
                }

                return true;
            });

        $this->assertTrue($routeRegistered, "`The controller {$controller} was not registered with the expected details`");

        return $this;
    }

    public function assertRouteNotRegistered(
        string $controller,
        string $name,
        string $uri,
    ): self {
        $routes = collect(app()->router->getRoutes());

        $routeRegistered = $routes
            ->contains(function (Route $route) use ($controller, $name, $uri) {
                $routeController = $route->getAction(0) ?? $route->getController() !== null
                    ? get_class($route->getController())
                    : null;

                if ($routeController !== $controller) {
                    return false;
                }

                if ($route->getName() !== $name) {
                    return false;
                }

                if ($route->uri() !== $uri) {
                    return false;
                }

                return true;
            });

        $this->assertFalse($routeRegistered, "`The controller {$controller} should not have been registered with the expected details`");

        return $this;
    }
}
