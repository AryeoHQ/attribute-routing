<?php

declare(strict_types=1);

namespace Support\Routing;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Support\Routing\Enums\Method;
use Support\Routing\Exceptions\NamespaceNotFound;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class RouteRegistrar
{
    protected null|string $middlewareGroup = null;

    /**
     * @param  array{path: string, middlewareGroup: string|null}  $directory
     */
    public function registerDirectory(array $directory): void
    {
        $files = (new Finder)->files()
            ->in($directory['path'])
            ->name('*Controller.php')
            ->sortByName();

        $this->middlewareGroup = data_get($directory, 'middlewareGroup');

        foreach ($files as $file) {
            $this->registerFile($file);
        }
    }

    public function registerFile(string|SplFileInfo $path): void
    {
        if (is_string($path)) {
            $path = new SplFileInfo($path);
        }

        $fullyQualifiedClassName = $this->fullyQualifiedClassNameFromFile($path);

        $this->processAttributes($fullyQualifiedClassName);
    }

    /**
     * @throws FileNotFoundException
     * @throws NamespaceNotFound
     */
    protected function fullyQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        if ($file->isFile() === false) {
            throw new FileNotFoundException($file->getPathname());
        }

        $contents = file_get_contents($file->getRealPath(), true);

        if (preg_match("/^namespace\s+([^;]+);/m", (string) $contents, $matches) === 0) {
            throw new NamespaceNotFound($file->getRealPath());
        }

        return trim($matches[1]).'\\'.pathinfo($file->getFilename())['filename'];
    }

    protected function processAttributes(string $className): void
    {
        if (! class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        foreach ($class->getMethods() as $method) {
            $this->registerRoute($class);
        }
    }

    /**
     * @template T of object
     *
     * @param  ReflectionClass<T>  $class
     */
    protected function registerRoute(ReflectionClass $class): void
    {
        foreach ($class->getMethods() as $method) {
            $attributes = $this->getAttributesForTheMethod($method);

            if (count($attributes) === 0) {
                continue;
            }

            $routeDetails = $this->getRouteDetails($attributes, $method, $class);

            foreach ($routeDetails->methods as $httpMethod) {
                /** @var Method $httpMethod */
                call_user_func([Route::class, $httpMethod->value], $routeDetails->uri, $routeDetails->action)
                    ->prefix($routeDetails->prefix)
                    ->name($routeDetails->name)
                    ->when($routeDetails->withTrashed, fn (\Illuminate\Routing\Route $route) => $route->withTrashed())
                    ->when($routeDetails->middleware, fn (\Illuminate\Routing\Route $route) => $route->middleware($routeDetails->middleware))
                    ->when(count($routeDetails->withoutMiddleware) > 0, fn (\Illuminate\Routing\Route $route) => $route->withoutMiddleware($routeDetails->withoutMiddleware));
            }
        }
    }

    /**
     * @return array<ReflectionAttribute<RoutingAttribute>>
     */
    protected function getAttributesForTheMethod(ReflectionMethod $method): array
    {
        return $method->getAttributes(Attributes\Contracts\RoutingAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
    }

    /**
     * @param  array<ReflectionAttribute<RoutingAttribute>>  $attributes
     * @param  ReflectionClass<object>  $class
     * @return object{name: string, uri: string, methods: array<Method>, action: class-string<object>|array{class-string<object>, non-empty-string}, prefix: ?string, withTrashed: ?bool, middleware: array<string>}
     */
    protected function getRouteDetails(array $attributes, ReflectionMethod $method, ReflectionClass $class): object
    {
        $attributes = collect($attributes)
            ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());
        /** @var Attributes\Route $routeAttribute */
        $routeAttribute = $attributes->firstWhere(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Route);
        /** @var Attributes\Middleware|null $middlewareAttribute */
        $middlewareAttribute = $attributes
            ->firstWhere(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Middleware);

        /** @var class-string<object>|array{class-string<object>, non-empty-string} */
        $action = $method->getName() === '__invoke'
            ? $class->getName()
            : [$class->getName(), $method->getName()];

        return (object) [
            'name' => $routeAttribute->name,
            'uri' => $routeAttribute->uri,
            'methods' => $routeAttribute->getMethods(),
            'action' => $action,
            'prefix' => $routeAttribute->prefix,
            'withTrashed' => $routeAttribute->withTrashed,
            'withoutMiddleware' => $routeAttribute->withoutMiddleware,
            'middleware' => $this->getMiddlewareStack($attributes),
        ];
    }

    /**
     * @param  Collection<int|string, RoutingAttribute>  $attributes
     * @return array<string>
     */
    private function getMiddlewareStack(Collection $attributes): array
    {
        return $attributes
            ->filter(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Middleware)
            ->map(fn (Attributes\Middleware $attribute) => $attribute->getMiddleware())
            ->when($this->middlewareGroup !== null, fn (Collection $collection) => $collection->prepend(Arr::wrap($this->middlewareGroup)))
            ->flatten()
            ->toArray();
    }
}
