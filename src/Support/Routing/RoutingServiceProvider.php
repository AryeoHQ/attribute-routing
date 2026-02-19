<?php

declare(strict_types=1);

namespace Support\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootViews();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../../config/routing.php' => config_path('routing.php'),
            ], 'config');
        }

        $this->registerRoutes();
    }

    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../resources/views/rector/rules', 'attribute-routing.rector.rules.samples');
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
            ->each(function (array $directory) use ($routeRegistrar) {
                /** @var array{path: string, middlewareGroup: string|null} $directory */
                $routeRegistrar->registerDirectory($directory);
            });

        return true;
    }

    /**
     * @return array<array{path: string, middlewareGroup: ?string}>
     */
    private function getRouteDirectories(): array
    {
        /** @var array<array{path: string, middlewareGroup: ?string}> */
        return config('routing.directories');
    }
}
