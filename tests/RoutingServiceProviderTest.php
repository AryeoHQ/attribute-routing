<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\DirectoryConfig;
use Support\Routing\Enums\Method;
use Support\Routing\RouteRegistrar;
use Support\Routing\RoutingServiceProvider;
use Tests\Fixtures\Middleware\GlobalMiddleware;

#[CoversClass(RoutingServiceProvider::class)]
class RoutingServiceProviderTest extends TestCase
{
    protected RouteRegistrar $routeRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routeRegistrar = app(RouteRegistrar::class);
    }

    #[Test]
    public function it_registers_routes_in_directories(): void
    {
        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'bar',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Show\Controller::class,
            name: 'bar.show',
            uri: 'bar/{bar}',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Show\Controller::class,
            name: 'foo.show',
            uri: 'foo/{foo}',
            httpMethod: Method::Get,
            middleware: null,
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Edit\Controller::class,
            name: 'foo.edit',
            uri: 'foo/{foo}/edit',
            httpMethod: Method::Get,
            middleware: null,
            withoutMiddleware: [
                GlobalMiddleware::class,
            ],
        );
    }

    #[Test]
    #[DefineEnvironment('withDomainConfig')]
    public function it_registers_routes_with_domain_from_config(): void
    {
        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'bar',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            domain: 'api.example.com',
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Show\Controller::class,
            name: 'foo.show',
            uri: 'foo/{foo}',
            httpMethod: Method::Get,
            middleware: null,
            domain: 'api.example.com',
        );
    }

    #[Test]
    public function it_caches_config_that_holds_a_directory_config(): void
    {
        $this->useProcessConfigCachePath();

        $this->artisan('config:cache')->assertSuccessful();

        $cached = require $this->app->getCachedConfigPath();

        $this->assertEquals(
            [new DirectoryConfig(path: app_path('Http/Controllers'), middlewareGroup: 'api')],
            $cached['routing']['directories'],
        );
    }

    #[Test]
    public function it_registers_routes_from_an_exported_directory_config(): void
    {
        $original = new DirectoryConfig(
            path: (string) __DIR__.'/Fixtures',
            middlewareGroup: 'api',
            prefix: 'cached',
            domain: 'cached.example.com',
        );

        $rebuilt = eval('return '.var_export([$original], true).';');

        $this->assertEquals([$original], $rebuilt);

        config(['routing.directories' => $rebuilt]);
        $this->app->register(RoutingServiceProvider::class, force: true);

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'cached/bar',
            httpMethod: Method::Get,
            middleware: ['api', 'auth', 'throttle:100,1'],
            domain: 'cached.example.com',
        );
    }

    protected function tearDown(): void
    {
        if ($this->configCachePath !== null) {
            File::delete($this->configCachePath);
            putenv('APP_CONFIG_CACHE');
            unset($_ENV['APP_CONFIG_CACHE'], $_SERVER['APP_CONFIG_CACHE']);
        }

        parent::tearDown();
    }

    protected ?string $configCachePath = null;

    protected function useProcessConfigCachePath(): void
    {
        $this->configCachePath = sys_get_temp_dir().'/attribute-routing-config-'.getmypid().'.php';

        putenv("APP_CONFIG_CACHE={$this->configCachePath}");
        $_ENV['APP_CONFIG_CACHE'] = $_SERVER['APP_CONFIG_CACHE'] = $this->configCachePath;
    }

    protected function withDomainConfig($app): void
    {
        $app['config']->set('routing.directories', [
            new DirectoryConfig(
                path: (string) __DIR__.'/Fixtures',
                domain: 'api.example.com',
            ),
        ]);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('routing.directories', [
            new DirectoryConfig(
                path: (string) __DIR__.'/Fixtures',
            ),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            RoutingServiceProvider::class,
        ];
    }
}
