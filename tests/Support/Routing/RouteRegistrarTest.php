<?php

namespace Tests\Support\Routing;

use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Enums\Method;
use Support\Routing\Exceptions\NamespaceNotFoundException;
use Support\Routing\RouteRegistrar;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Tests\Fixtures;
use Tests\TestCase;

#[CoversClass(RouteRegistrar::class)]
class RouteRegistrarTest extends TestCase
{
    public function test_registrar_can_register_a_file(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('Bar/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'bar',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            withTrashed: false,
        );
    }

    public function test_registrar_can_register_multiple_routes_for_the_same_controller(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('Foo/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );
    }

    public function test_registrar_can_register_a_directory(): void
    {
        $this->routeRegistrar->registerDirectory(__DIR__.'/../../Fixtures');

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'bar',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Show\Controller::class,
            name: 'bar.show',
            uri: 'bar/{bar}',
            httpMethod: Method::Get,
            middleware: ['auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Controller::class,
            name: 'foo',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );
    }

    public function test_it_throws_file_not_found_exception(): void
    {
        $this->expectException(FileNotFoundException::class);

        $this->routeRegistrar->registerFile($this->getFixture('Foo/NotFoundController.php'));
    }

    public function test_it_throws_namespace_not_found_exception(): void
    {
        $this->expectException(NamespaceNotFoundException::class);

        $this->routeRegistrar->registerFile($this->getFixture('Foo/MissingNamespace.php'));
    }
}
