<?php

declare(strict_types=1);

namespace Tests\Fixtures\Middleware;

class GlobalMiddleware
{
    public function handle($request, $next)
    {
        return $next($request);
    }
}
