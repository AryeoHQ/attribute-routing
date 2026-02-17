<?php

declare(strict_types=1);

namespace Tooling\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Routing\Attributes\Route;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class InvokableControllerHasRouteAttribute extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        $invokeMethod = $node->getMethod('__invoke');

        return $node->name->toString() === 'Controller'
            && ! $node->isAbstract()
            && $invokeMethod !== null
            && ! $this->hasAttribute($invokeMethod, Route::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Invokable controllers register routes using the \Support\Routing\Attributes\Route attribute.',
            line: $node->name->getStartLine(),
            identifier: 'routing.attributes.route'
        );
    }
}
