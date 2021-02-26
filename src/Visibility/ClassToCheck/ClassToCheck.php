<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassToCheck;


use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

interface ClassToCheck
{
    public function checkVisibilityViolation(): VisibilityViolations;
}