<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Violations\UseStatements;

use Lukasz93P\ClassVisibility\Repository\Reflection\ExtendedReflectionClass;
use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\VisibilityException;
use ReflectionException;

class ReflectionUseStatements implements UseStatements
{
    private function __construct()
    {
    }

    public static function create(): static
    {
        return new self();
    }

    public function getClassesUsedBy(ClassName $className): UniqueValueObjectsCollection
    {
        try {
            $class = new ExtendedReflectionClass($className->toString());
        } catch (ReflectionException $reflectionException) {
            throw VisibilityException::fromReason($reflectionException);
        }

        return UniqueValueObjectsCollection::create(
            array_map(static fn(string $usedClassName) => ClassName::create($usedClassName), $class->getUseStatements())
        );
    }
}