<?php

declare(strict_types=1);

use Support\Routing\DirectoryConfig;

return [
    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered. Each directory can optionally
     * specify a middleware group, prefix, or domain to be applied to
     * all routes.
     */
    'directories' => [
        new DirectoryConfig(
            path: app_path('Http/Controllers'),
            middlewareGroup: 'api',
        ),
    ],
];
