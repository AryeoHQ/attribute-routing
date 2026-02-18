<?php

declare(strict_types=1);

namespace Tests\Tooling\PhpStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\GetsFixtures;
use Tooling\PhpStan\Rules\InvokableControllerHasRouteAttribute;

#[CoversClass(InvokableControllerHasRouteAttribute::class)]
class InvokableControllerHasRouteAttributeTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new InvokableControllerHasRouteAttribute;
    }

    #[Test]
    public function it_should_return_true_if_the_class_is_an_invokable_controller_and_has_a_route_attribute(): void
    {
        $this->analyse([$this->getFixturePath('Foo/Index/Controller.php')], []);
    }

    #[Test]
    public function it_should_return_an_error_if_the_class_is_an_invokable_controller_and_does_not_have_a_route_attribute(): void
    {
        $this->analyse([$this->getFixturePath('Foo/Delete/Controller.php')], [
            [
                'Invokable controllers register routes using the \Support\Routing\Attributes\Route attribute.',
                7,
            ],
        ]);
    }
}
