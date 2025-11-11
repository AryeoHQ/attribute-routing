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

    #[Test]
    public function registrar_can_apply_class_level_prefix(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithClassPrefix/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithClassPrefix\Controller::class,
            name: 'with-class-prefix',
            uri: 'api/resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_apply_method_level_prefix(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithMethodPrefix/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithMethodPrefix\Controller::class,
            name: 'with-method-prefix',
            uri: 'api/resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_combine_class_and_method_level_prefix(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithBothPrefixes/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithBothPrefixes\Controller::class,
            name: 'with-both-prefixes',
            uri: 'api/v1/resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_combine_all_prefix_types(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithAllPrefixes/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithAllPrefixes\Controller::class,
            name: 'with-all-prefixes',
            uri: 'api/v1/admin/resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }
}
