# Laravel Routing
This package provides attributes for regsitering routes and middleware on Laravel controllers.

## Installation
```bash
composer require aryeo/laravel-routing
```

## Configuration

If your controller are located in a different directory than `app/Http/Controllers`, you can publish the config file to override what directorys the route register will look in.

```sh
php artisan vendor:publish --provider="Support\Routing\RoutingServiceProvider" --tag="config"
```

```php
return [
    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     */
    'directories' => [
        app_path('Domains'),
        //...
    ],
];
```

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
        prefix: 'v1'
        methods: Method::Get,
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::get('users', Controller::class)
    ->prefix('v1')
    ->name('users.index');
```

#### Multiple methods

The `methods` prop can take a single method or an array of methods. If an array is passed in, it will register all methods. Use of the `Method` enum is required.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.update',
        uri: 'users/{user}',
        prefix: 'v1'
        methods: [Method::Put, Method::Patch],
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::match(['PUT', 'PATCH'], 'users/{user}', Controller::class)
    ->prefix('v1')
    ->name('users.update');
```

#### Accesing soft deleted models

The `withTrashed` prop indicates if you want the route to find soft deleted models. It is set to `false` by default.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.index',
        uri: 'users',
        prefix: 'v1'
        methods: Method::Get,
        withTrashed: true
    )]
    public function __invoke() {}
}
```

This attribute will automatically register this route:
```php
Route::get('users', Controller::class)
    ->prefix('v1')
    ->name('users.index')
    ->withTrashed();
```

### Middleware Attribute

The middleware attribute allows you to define any additonal middleware to be applied to your route.

```php
use Support\Routing\Attributes\Route;
use Support\Routing\Attributes\Middleware;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(
        name: 'users.index',
        uri: 'users',
        prefix: 'v1'
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
    ->prefix('v1')
    ->name('users.index')
    ->middleware(['auth', 'throttle:100,1']);
```
