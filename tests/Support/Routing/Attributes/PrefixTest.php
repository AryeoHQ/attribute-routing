<?php

declare(strict_types=1);

namespace Tests\Support\Routing\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Prefix;
use Tests\TestCase;

#[CoversClass(Prefix::class)]
class PrefixTest extends TestCase
{
    #[Test]
    public function prefix_can_be_constructed(): void
    {
        $prefix = new Prefix(prefix: 'api/v1');

        $this->assertSame('api/v1', $prefix->prefix);
    }
}
