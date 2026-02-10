<?php

declare(strict_types=1);

return [
    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered. Each directory can optionally
     * specify a middleware group to be applied to all routes.
     */
    'directories' => [
        [
            'path' => app_path('Http/Controllers'),
            'middlewareGroup' => 'api', // Optional: middleware group name or null
            //'prefix' => 'v1', // Optional: prefix for all routes in this directory
        ],
    ],
];
