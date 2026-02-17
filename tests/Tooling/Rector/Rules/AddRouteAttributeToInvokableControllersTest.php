<?php

declare(strict_types=1);

namespace Tests\Tooling\Rector\Rules;

use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Routing\Attributes\Route;
use Tests\Concerns\GetsFixtures;
use Tests\TestCase;
use Tooling\Rector\Rules\AddRouteAttributeToInvokableControllers;
use Tooling\Rector\Rules\Provides\ValidatesAttributes;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;

#[CoversClass(AddRouteAttributeToInvokableControllers::class)]
class AddRouteAttributeToInvokableControllersTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;
    use ValidatesAttributes;
    use ValidatesInheritance;

    #[Test]
    public function it_adds_the_route_attribute_to_the_invoke_method_when_it_is_not_defined(): void
    {
        $classNode = $this->getClassNode($this->getFixturePath('Foo/Delete/Controller.php'));
        $invokeMethod = $classNode->getMethod('__invoke');

        $rule = $this->resolveRule(AddRouteAttributeToInvokableControllers::class);

        $this->assertFalse($rule->hasAttribute($invokeMethod, Route::class));

        $result = $rule->refactor($classNode);

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($rule->hasAttribute($result->getMethod('__invoke'), Route::class));
    }

    #[Test]
    public function it_does_not_modify_the_ivokable_controller_when_the_route_attribute_is_already_defined(): void
    {
        $classNode = $this->getClassNode($this->getFixturePath('Foo/Index/Controller.php'));
        $invokeMethod = $classNode->getMethod('__invoke');

        $rule = $this->resolveRule(AddRouteAttributeToInvokableControllers::class);

        $this->assertTrue($rule->hasAttribute($invokeMethod, Route::class));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}
