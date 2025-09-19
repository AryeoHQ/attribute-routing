<?php

namespace Tests;

use Orchestra\Testbench;
use Support\Routing\RouteRegistrar;

abstract class TestCase extends Testbench\TestCase
{
    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;

    protected RouteRegistrar $routeRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $router = app()->router;

        $this->routeRegistrar = (new RouteRegistrar(app()->router))
            ->useBasePath(__DIR__.'/Fixtures')
            ->useRootNamespace('Tests\\');
    }
}
