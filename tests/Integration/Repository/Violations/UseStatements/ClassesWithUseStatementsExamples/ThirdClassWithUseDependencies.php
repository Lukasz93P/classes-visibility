<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples;

use Lukasz93P\ClassVisibility\Facade\ClassesVisibility;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

class ThirdClassWithUseDependencies implements ClassWithUseDependencies
{
    public function getUsedClasses(): array
    {
        return [
            ClassesVisibility::class,
            ClassName::class,
        ];
    }

}