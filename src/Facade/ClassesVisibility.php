<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Facade;


use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

interface ClassesVisibility
{
    public function getViolations(NamespacePath $namespace): VisibilityViolations;
}