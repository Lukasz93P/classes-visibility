<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassToCheck;


use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;

interface ClassToCheckRepository
{
    /**
     * @return ClassToCheck[]
     */
    public function getAllClasses(NamespacePath $namespaceToGetClassesFrom): array;
}