<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

interface Visibility
{
    public function canBeUsedBy(ClassName $className): bool;
}
