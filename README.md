# Actions
This package provides the base for HTTP request authorizers and validators in Laravel.

## Installation
```bash
composer require aryeo/laravel-authorizer-validator
```

## Overview
This package offers a base abstract class for authorizers and validators for your Laravel application, splitting the FormRequest into discrete responsibilities.

## Usage

### Genrate Authorizers and Validators

Authorizers and Validators can be generated via artisan commands

```sh
php artisan make:authorizer MyAuthorizer
php artisan make:validator MyValidator
```

### Defining Authorizers and Validators

Authorizers

```php
use Support\Http\Authorizer as BaseAuthorizer;

final class MyAuthorizer extends BaseAuthorizer
{
    public function authorize(): bool
    {
        // The authorization logic goes here.
    }
}
```

Validators 

```php

use Support\Http\Validator as BaseValidator;

final class MyValidator extends BaseValidator
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // The validation rules go here
    }
}
```

### Using Authorizers and Validators

Authorizers and Validators split the traditional Laravel `FormRequest` into 2 discrete classes. They can be leveraged in Controllers like tis

Directly
```php
public function __invoke(MyAuthorizer $authorizer, MyValidator $validator)
{
    // controller logic
}
```