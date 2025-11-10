<?php

declare(strict_types=1);

namespace Support\Routing\Exceptions;

use LogicException;

class NamespaceNotFound extends LogicException
{
    public function __construct(string $filePath)
    {
        parent::__construct("No namespace found in file: {$filePath}");
    }
}
