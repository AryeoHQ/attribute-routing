<?php

declare(strict_types=1);

namespace Support\Routing\Enums;

enum Method: string
{
    case Get = 'GET';
    case Post = 'POST';
    case Put = 'PUT';
    case Patch = 'PATCH';
    case Delete = 'DELETE';
    case Options = 'OPTIONS';
    case Any = 'ANY';
}
