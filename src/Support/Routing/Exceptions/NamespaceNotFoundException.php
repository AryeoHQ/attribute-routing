<?php

declare(strict_types=1);

namespace Support\Routing\Exceptions;

use Exception;

class NamespaceNotFoundException extends Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct("No namespace found in file: {$filePath}");
    }
}
