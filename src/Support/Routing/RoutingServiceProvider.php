<?php

declare(strict_types=1);

namespace Support\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
    }

    private function registerRoutes(): bool
    {
        if ($this->app->routesAreCached()) {
            return false;
        }

        $routeRegistrar = $this->app->make(RouteRegistrar::class, [app()->router]);

        collect($this->getRouteDirectories())
            ->each(function (string $directory) use ($routeRegistrar) {
                $routeRegistrar
                    ->useRootNamespace(app()->getNamespace())
                    ->useBasePath($directory)
                    ->registerDirectory($directory);
            });
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [
        ];
    }

    private function getRouteDirectories(): array
    {
        return config('routing.directories');
    }
}
