<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Violations\UseStatements;


use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;

interface UseStatements
{
    public function getClassesUsedBy(ClassName $className): UniqueValueObjectsCollection;
}