<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples;

use Lukasz93P\ClassVisibility\Visibility\Visibilities\PrivateVisibility;
use RuntimeException;
use Throwable;

class SecondClassWithUseDependencies implements ClassWithUseDependencies
{
    public function getUsedClasses(): array
    {
        return [
            PrivateVisibility::class,
            RuntimeException::class,
            Throwable::class,
        ];
    }
}