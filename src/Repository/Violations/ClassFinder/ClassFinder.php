<?php

declare(strict_types=1);


namespace Lukasz93P\ClassVisibility\Repository\Violations\ClassFinder;


use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;

interface ClassFinder
{
    /**
     * @return string[]
     */
    public function getAllClassesNames(NamespacePath $namespace): array;
}