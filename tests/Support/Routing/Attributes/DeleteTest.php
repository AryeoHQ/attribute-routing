<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Delete;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Delete::class)]
class DeleteTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $delete = new Delete(
            uri: 'resource/{id}',
            name: 'resource.destroy',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $delete);
    }

    #[Test]
    public function get_methods(): void
    {
        $delete = new Delete(
            uri: 'resource/{id}',
            name: 'resource.destroy',
        );

        $this->assertEquals([Method::Delete], $delete->getMethods());
    }

    #[Test]
    public function it_can_have_optional_name(): void
    {
        $delete = new Delete(uri: 'resource/{id}');

        $this->assertNull($delete->name);
        $this->assertEquals('resource/{id}', $delete->uri);
    }
}
