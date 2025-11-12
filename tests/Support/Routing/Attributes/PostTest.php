<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Attributes\Post;
use Support\Routing\Enums\Method;
use Tests\TestCase;

#[CoversClass(Post::class)]
class PostTest extends TestCase
{
    #[Test]
    public function it_is_instance_of_routing_attribute(): void
    {
        $post = new Post(
            uri: 'resource',
            name: 'resource.store',
        );

        $this->assertInstanceOf(RoutingAttribute::class, $post);
    }

    #[Test]
    public function get_methods(): void
    {
        $post = new Post(
            uri: 'resource',
            name: 'resource.store',
        );

        $this->assertEquals([Method::Post], $post->getMethods());
    }

    #[Test]
    public function it_can_have_optional_name(): void
    {
        $post = new Post(uri: 'resource');

        $this->assertNull($post->name);
        $this->assertEquals('resource', $post->uri);
    }
}
