<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples;

interface ClassWithUseDependencies
{
    public function getUsedClasses(): array;
}