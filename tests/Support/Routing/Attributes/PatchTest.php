<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Patch;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Patch::class)]
class PatchTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $patch = new Patch(
            uri: 'resource/{id}',
            name: 'resource.update',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $patch);
    }

    #[Test]
    public function get_methods(): void
    {
        $patch = new Patch(
            uri: 'resource/{id}',
            name: 'resource.update',
        );

        $this->assertEquals([Method::Patch], $patch->getMethods());
    }

    #[Test]
    public function it_can_have_optional_name(): void
    {
        $patch = new Patch(uri: 'resource/{id}');

        $this->assertNull($patch->name);
        $this->assertEquals('resource/{id}', $patch->uri);
    }
}
