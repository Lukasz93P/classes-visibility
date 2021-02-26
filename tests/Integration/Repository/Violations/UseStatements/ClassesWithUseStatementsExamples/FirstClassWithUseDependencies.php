<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples;

use Iterator as It;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\Visibility as V;

class FirstClassWithUseDependencies implements ClassWithUseDependencies
{
    public function getUsedClasses(): array
    {
        return [
            It::class,
            V::class,
        ];
    }
}