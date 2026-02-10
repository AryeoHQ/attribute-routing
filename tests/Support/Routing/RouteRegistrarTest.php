<?php

declare(strict_types=1);

namespace Tests\Support\Routing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Enums\Method;
use Support\Routing\Exceptions\NamespaceNotFound;
use Support\Routing\RouteRegistrar;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Tests\Fixtures;
use Tests\TestCase;

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
    public function registrar_can_skip_a_file_with_no_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('Bar/Index/Controller.php'));

        $this->assertRouteNotRegistered(
            controller: Fixtures\Bar\Index\Controller::class,
            name: 'bar.index',
            uri: 'bar/index',
        );
    }

    #[Test]
    public function registrar_can_register_a_route_with_no_middleware_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('Foo/Show/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Show\Controller::class,
            name: 'foo.show',
            uri: 'foo/{foo}',
            httpMethod: Method::Get,
            middleware: null,
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_multiple_routes_for_the_same_controller(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('Foo/Index/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );
    }

    #[Test]
    public function registrar_can_register_a_route_with_a_prefix_in_config(): void
    {
        $this->routeRegistrar->registerDirectory([
            'path' => __DIR__.'/../../Fixtures',
            'middlewareGroup' => 'api',
            'prefix' => 'api',
        ]);

        $this->routeRegistrar->registerFile($this->getFixture('Foo/Index/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'api/v1/foo',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'api/v1/foo',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: true,
        );
    }

    #[Test]
    public function registrar_can_register_a_directory(): void
    {
        $this->routeRegistrar->registerDirectory([
            'path' => __DIR__.'/../../Fixtures',
            'middlewareGroup' => 'api',
        ]);

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Controller::class,
            name: 'bar',
            uri: 'bar',
            httpMethod: Method::Get,
            middleware: ['api', 'auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Bar\Show\Controller::class,
            name: 'bar.show',
            uri: 'bar/{bar}',
            httpMethod: Method::Get,
            middleware: ['api', 'auth', 'throttle:100,1'],
            withTrashed: false,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Put,
            middleware: ['api', 'auth'],
            withTrashed: true,
        );

        $this->assertRouteRegistered(
            controller: Fixtures\Foo\Index\Controller::class,
            name: 'foo.index',
            uri: 'v1/foo',
            httpMethod: Method::Patch,
            middleware: ['api', 'auth'],
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
