<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Facade;


use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

interface ClassesVisibility
{
    /**
     * @param NamespacePath $namespaces
     */
    public function getViolations(array $namespaces): VisibilityViolations;
}