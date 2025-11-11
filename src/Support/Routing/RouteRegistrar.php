<?php

declare(strict_types=1);

namespace Support\Routing;

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
    /**
     * @var array<string>
     */
    protected array $middleware = [];

    public function registerDirectory(string $directory): void
    {
        $files = (new Finder)->files()
            ->in($directory)
            ->name('*Controller.php')
            ->sortByName();

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
                    ->middleware($routeDetails->middleware);
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
        /** @var Attributes\Middleware $middlewareAttribute */
        $middlewareAttribute = $attributes->firstWhere(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Middleware);

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
            'middleware' => $middlewareAttribute->getMiddleware(),
        ];
    }
}
