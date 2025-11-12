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
    public function registrar_can_register_get_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithGetAttribute/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithGetAttribute\Controller::class,
            name: 'resource.index',
            uri: 'resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_post_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithPostAttribute/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithPostAttribute\Controller::class,
            name: 'resource.store',
            uri: 'resource',
            httpMethod: Method::Post,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_put_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithPutAttribute/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithPutAttribute\Controller::class,
            name: 'resource.update',
            uri: 'resource/{id}',
            httpMethod: Method::Put,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_patch_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithPatchAttribute/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithPatchAttribute\Controller::class,
            name: 'resource.patch',
            uri: 'resource/{id}',
            httpMethod: Method::Patch,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_delete_attribute(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithDeleteAttribute/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithDeleteAttribute\Controller::class,
            name: 'resource.destroy',
            uri: 'resource/{id}',
            httpMethod: Method::Delete,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_get_attribute_on_invokable_controller(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithGetAttributeOnClass/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithGetAttributeOnClass\Controller::class,
            name: 'invokable.resource.index',
            uri: 'invokable-resource',
            httpMethod: Method::Get,
            middleware: ['auth'],
            withTrashed: false,
        );
    }

    #[Test]
    public function registrar_can_register_post_attribute_on_invokable_controller(): void
    {
        $this->routeRegistrar->registerFile($this->getFixture('WithPostAttributeOnClass/Controller.php'));

        $this->assertRouteRegistered(
            controller: Fixtures\WithPostAttributeOnClass\Controller::class,
            name: 'invokable.resource.store',
            uri: 'invokable-resource',
            httpMethod: Method::Post,
            middleware: ['auth'],
            withTrashed: false,
        );
    }
}
