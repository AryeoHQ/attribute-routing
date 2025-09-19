<?php

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Route::class)]
class RouteTest extends TestCase
{
    public function test_it_is_instance_of_routing_attribute(): void
    {
        $route = new Route(
            name: 'test',
            uri: 'test',
            methods: Method::Get,
        );

        $this->assertInstanceOf(RoutingAttribute::class, $route);
    }

    public function test_get_methods(): void
    {
        $route1 = new Route(
            name: 'test',
            uri: 'test',
            methods: Method::Get,
        );

        $route2 = new Route(
            name: 'test',
            uri: 'test',
            methods: [Method::Put, Method::Patch],
        );

        $this->assertEquals([Method::Get], $route1->getMethods(), 'The route methods should be an array of uppercase strings.');
        $this->assertEquals([Method::Put, Method::Patch], $route2->getMethods(), 'The route methods should be an array of uppercase strings.');
    }
}
