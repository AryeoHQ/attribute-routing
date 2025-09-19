<?php

namespace Tests\Support\Routing\Attributes;
use Tests\Fixtures\Bar;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function test_route_attribute()
    {
        $this->routeRegistrar->registerClass(Bar\Controller::class);

        dd($this->app->router->getRoutes());
    }
}
