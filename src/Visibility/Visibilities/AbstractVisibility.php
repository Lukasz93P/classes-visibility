<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

abstract class AbstractVisibility implements Visibility
{
    protected ClassName $className;

    public function __construct(string $className)
    {
        $this->className = ClassName::create($className);
    }
}
