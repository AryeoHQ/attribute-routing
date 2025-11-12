<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Get;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Get::class)]
class GetTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $get = new Get(
            uri: 'resource',
            name: 'resource.index',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $get);
    }

    #[Test]
    public function get_methods(): void
    {
        $get = new Get(
            uri: 'resource',
            name: 'resource.index',
        );

        $this->assertEquals([Method::Get], $get->getMethods());
    }

    #[Test]
    public function it_can_have_optional_name(): void
    {
        $get = new Get(uri: 'resource');

        $this->assertNull($get->name);
        $this->assertEquals('resource', $get->uri);
    }
}
