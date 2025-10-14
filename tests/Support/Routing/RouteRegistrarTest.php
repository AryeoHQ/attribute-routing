<?php

namespace Tests\Support\Routing;

use Tests\Fixtures;
use Tests\TestCase;
use Support\Routing\Enums\Method;
use Support\Routing\RouteRegistrar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Routing\Exceptions\NamespaceNotFound;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

#[CoversClass(RouteRegistrar::class)]
class RouteRegistrarTest extends TestCase
{
    #[Test]
    public function registrar_can_register_a_file(): void
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

    #[Test]
    public function registrar_can_register_multiple_routes_for_the_same_controller(): void
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function registrar_can_register_a_directory(): void
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

    #[Test]
    public function it_throws_file_not_found_exception(): void
    {
        $this->expectException(FileNotFoundException::class);

        $this->routeRegistrar->registerFile($this->getFixture('Foo/NotFoundController.php'));
    }

    #[Test]
    public function it_throws_namespace_not_found_exception(): void
    {
        $this->expectException(NamespaceNotFound::class);

        $this->routeRegistrar->registerFile($this->getFixture('Foo/MissingNamespace.php'));
    }
}
