<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Put;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Put::class)]
class PutTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $put = new Put(
            uri: 'resource/{id}',
            name: 'resource.update',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $put);
    }

    #[Test]
    public function get_methods(): void
    {
        $put = new Put(
            uri: 'resource/{id}',
            name: 'resource.update',
        );

        $this->assertEquals([Method::Put], $put->getMethods());
    }

    #[Test]
    public function it_can_have_optional_name(): void
    {
        $put = new Put(uri: 'resource/{id}');

        $this->assertNull($put->name);
        $this->assertEquals('resource/{id}', $put->uri);
    }
}
