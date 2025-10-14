<?php

namespace Tests\Support\Routing\Attributes;

use Tests\TestCase;
use Support\Routing\Enums\Method;
use Support\Routing\Attributes\Route;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[CoversClass(Route::class)]
class RouteTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $route = new Route(
            name: 'test',
            uri: 'test',
            methods: Method::Get,
        );

        $this->assertInstanceOf(RoutingAttribute::class, $route);
    }

    #[Test]
    public function get_methods(): void
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
