<?php

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Enums\Method;
use Support\Routing\RouteRegistrar;
use Support\Routing\RoutingServiceProvider;

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
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('routing.directories', [
            __DIR__.'/Fixtures',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            RoutingServiceProvider::class,
        ];
    }
}
