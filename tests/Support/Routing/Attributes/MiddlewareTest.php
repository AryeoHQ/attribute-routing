<?php

namespace Tests\Support\Routing\Attributes;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Middleware;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Attributes\Contracts\RoutingAttribute;

#[CoversClass(Middleware::class)]
class MiddlewareTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $middleware = new Middleware(
            middleware: 'auth',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $middleware);
    }

    #[Test]
    public function get_middleware(): void
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
