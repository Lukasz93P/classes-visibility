<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

class PublicVisibility extends AbstractVisibility
{
    public function canBeUsedBy(ClassName $className): bool
    {
        return true;
    }
}