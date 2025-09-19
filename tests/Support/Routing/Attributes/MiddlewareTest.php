<?php

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Middleware;
use Tests\TestCase;

#[CoversClass(Middleware::class)]
class MiddlewareTest extends TestCase
{
    public function test_it_is_instance_of_routing_attribute(): void
    {
        $middleware = new Middleware(
            middleware: 'auth',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $middleware);
    }

    public function test_get_middleware(): void
    {
        $middleware1 = new Middleware(
            middleware: 'auth',
        );

        $middleware2 = new Middleware(
            middleware: ['auth', 'throttle:100,1'],
        );

        $this->assertEquals(['auth'], $middleware1->getMiddleware(), 'The middleware should be an array of strings.');
        $this->assertEquals(['auth', 'throttle:100,1'], $middleware2->getMiddleware(), 'The middleware should be an array of strings.');
    }
}
