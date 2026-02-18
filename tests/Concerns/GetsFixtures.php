<?php

declare(strict_types=1);

namespace Tests\Concerns;

trait GetsFixtures
{
    protected function getFixturePath(string $filename): string
    {
        return __DIR__.'/../Fixtures/'.$filename;
    }
}
