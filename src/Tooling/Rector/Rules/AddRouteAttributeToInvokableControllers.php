<?php

declare(strict_types=1);

namespace Tooling\Rector\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Support\Routing\Attributes\Route;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Samples\Attributes\Sample;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add the Route attribute to the invokable controller class')]
#[NodeType(Class_::class)]
#[Sample('attribute-routing.rector.rules.samples')]
final class AddRouteAttributeToInvokableControllers extends Rule
{
    protected null|ClassMethod $invokeMethod = null;

    public function shouldHandle(Node $node): bool
    {
        $this->invokeMethod = $node->getMethod('__invoke');

        return $node->name->toString() === 'Controller'
            && ! $node->isAbstract()
            && $this->invokeMethod !== null
            && ! $this->hasAttribute($this->invokeMethod, Route::class);
    }

    public function handle(Node $node): Node
    {
        $node->stmts = collect($node->stmts)
            ->map(function (Node $stmt) {
                if (! $stmt instanceof ClassMethod) {
                    return $stmt;
                }

                if ($stmt->name->toString() !== '__invoke') {
                    return $stmt;
                }

                if ($this->hasAttribute($stmt, Route::class)) {
                    return $stmt;
                }

                return $this->addAttribute($stmt, Route::class);
            })
            ->toArray();

        return $node;
    }
}
