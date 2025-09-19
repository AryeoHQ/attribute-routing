<?php

namespace Support\Routing;

use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;
use Support\Routing\Attributes\Contracts\RoutingAttribute;
use Symfony\Component\Finder\Finder;

class RouteRegistrar
{
    private Router $router;

    protected string $basePath;

    protected string $rootNamespace;

    protected array $middleware = [];

    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->useBasePath(app()->path());
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $basePath);

        return $this;
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = rtrim(str_replace('/', '\\', $rootNamespace), '\\').'\\';

        return $this;
    }

    public function registerDirectory(string $directory): void
    {
        $files = (new Finder)->files()->in($directory)->name('*Controller.php')->sortByName();

        collect($files)
            ->each(fn (SplFileInfo $file) => $this->registerFile($file));
    }

    public function registerFile(SplFileInfo $path): void
    {
        $path = new SplFileInfo($path);

        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($path);

        $this->processAttributes($fullyQualifiedClassName);
    }

    public function registerClass(string $class): void
    {
        $this->processAttributes($class);
    }

    protected function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst($this->basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $this->rootNamespace.$class;
    }

    protected function processAttributes(string $className): void
    {
        if (! class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        foreach ($class->getMethods() as $method) {
            $this->registerRoute($class, $method);
        }
    }

    protected function registerRoute(ReflectionClass $class): void
    {
        foreach ($class->getMethods() as $method) {
            [$attributes] = $this->getAttributesForTheMethod($method);

            [$name, $uri, $httpMethods, $action, $prefix, $withTrashed, $middleware] = $this->getRouteDetails($attributes, $method, $class);

            foreach ($httpMethods as $httpMethod) {
                Route::$httpMethod($uri, $action)
                    ->prefix($prefix)
                    ->name($name)
                    ->when($withTrashed, fn (\Illuminate\Routing\Route $route) => $route->withTrashed())
                    ->middleware($middleware);
            }
        }
    }

    protected function getAttributesForTheMethod(ReflectionMethod $method): array
    {
        $attributes = $method->getAttributes(Attributes\Contracts\RoutingAttribute::class, ReflectionAttribute::IS_INSTANCEOF);

        return [$attributes];
    }

    protected function getRouteDetails(array $attributes, ReflectionMethod $method, ReflectionClass $class): array
    {
        $attributes = collect($attributes)
            ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());

        $routeAttribute = $attributes->firstWhere(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Route);
        $middlewareAttribute = $attributes->firstWhere(fn (RoutingAttribute $attribute) => $attribute instanceof Attributes\Middleware);

        $action = $method->getName() === '__invoke'
            ? $class->getName()
            : [$class->getName(), $method->getName()];

        return [
            $routeAttribute->name,
            $routeAttribute->uri,
            $routeAttribute->getMethods(),
            $action,
            $routeAttribute->prefix,
            $routeAttribute->withTrashed,
            $middlewareAttribute->middleware,
        ];
    }
}
