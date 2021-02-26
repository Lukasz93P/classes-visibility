<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Visibilities;

use Attribute;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

#[Attribute(Attribute::TARGET_CLASS)]
class PrivateVisibility extends AbstractVisibility
{
    public function canBeUsedBy(ClassName $className): bool
    {
        return $this->className->isClassInExactNamespace($className);
    }
}
