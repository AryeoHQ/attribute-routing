<?php

declare(strict_types=1);

namespace Support\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../../config/routing.php' => config_path('routing.php'),
            ], 'config');
        }

        $this->registerRoutes();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/routing.php', 'routing');
    }

    private function registerRoutes(): bool
    {
        if (app()->routesAreCached()) {
            return false;
        }

        $routeRegistrar = app()->make(RouteRegistrar::class);

        collect($this->getRouteDirectories())
            ->each(function (string $directory) use ($routeRegistrar) {
                $routeRegistrar->registerDirectory($directory);
            });

        return true;
    }

    /**
     * @return array<string>
     */
    private function getRouteDirectories(): array
    {
        /** @var array<string> */
        return config('routing.directories');
    }
}
