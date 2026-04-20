# Attribute Routing
This package provides attributes for registering routes and middleware on Laravel controllers.

## Installation
```bash
composer require aryeo/attribute-routing
```

## Configuration

If your controller are located in a different directory than `app/Http/Controllers`, you can publish the config file to override what directories the route register will look in.

```sh
php artisan vendor:publish --provider="Support\Routing\RoutingServiceProvider" --tag="config"
```

```php
use Support\Routing\DirectoryConfig;

return [
    'directories' => [
        new DirectoryConfig(
            path: app_path('Http/Controllers'),
            middlewareGroup: 'api',
        ),
    ],
];
```

Each `DirectoryConfig` accepts the following parameters:

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `path` | `string` | *(required)* | Directory to scan for controllers |
| `middlewareGroup` | `?string` | `null` | Middleware group applied to all routes |
| `prefix` | `?string` | `null` | URI prefix for all routes |
| `domain` | `?string` | `null` | Restrict routes to a specific domain |

### Domain restriction

You can restrict all routes in a directory to a specific domain:

```php
new DirectoryConfig(
    path: app_path('Http/Api/V2'),
    middlewareGroup: 'api',
    domain: 'api.example.com',
),
```

All routes registered from that directory will only respond to requests on `api.example.com`.

## Usage

### Route Attribute

The attribute is added above the methods in your controller to register them.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.index',
        uri: 'users',
        methods: Method::Get,
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::get('users', Controller::class)
    ->name('users.index');
```

#### Multiple methods

The `methods` prop can take a single method or an array of methods. If an array is passed in, it will register all methods.
> Use of the `Method` enum is required.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.update',
        uri: 'users/{user}',
        methods: [Method::Put, Method::Patch],
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::match(['PUT', 'PATCH'], 'users/{user}', Controller::class)
    ->name('users.update');
```

#### Accessing soft deleted models

The `withTrashed` prop indicates if you want the route to find soft deleted models. It is set to `false` by default.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.index',
        uri: 'users',
        methods: Method::Get,
        withTrashed: true
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::get('users', Controller::class)
    ->name('users.index')
    ->withTrashed();
```

### Middleware Attribute

The middleware attribute allows you to define any additional middleware to be applied to your route.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Attributes\Middleware;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.index',
        uri: 'users',
        methods: Method::Get,
    )]
    #[Middleware([
        'auth',
        'throttle:100,1',
    ])]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::get('users', Controller::class)
    ->name('users.index')
    ->middleware(['auth', 'throttle:100,1']);
```
