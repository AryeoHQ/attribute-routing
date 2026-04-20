<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Enums\Method;
use Support\Routing\RouteRegistrar;
use Support\Routing\RoutingServiceProvider;

#[CoversClass(RoutingServiceProvider::class)]
class RoutingServiceProviderWithDomainTest extends TestCase
{
    protected RouteRegistrar $routeRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routeRegistrar = app(RouteRegistrar::class);
    }

    #[Test]
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

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('routing.directories', [
            [
                'path' => (string) __DIR__.'/Fixtures',
                'middlewareGroup' => null,
                'domain' => 'api.example.com',
            ],
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            RoutingServiceProvider::class,
        ];
    }
}
