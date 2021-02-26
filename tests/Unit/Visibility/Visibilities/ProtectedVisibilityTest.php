<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\ProtectedVisibility;

class ProtectedVisibilityTest extends VisibilityTest
{
    /**
     * @dataProvider classNamesFromExactNamespaceProvider
     */
    public function testCanBeUsedInExactSameNamespace(
        ClassName $className,
        ClassName $classFromExactSameNamespace
    ): void {
        $visibility = new ProtectedVisibility($className->toString());

        self::assertTrue($visibility->canBeUsedBy($classFromExactSameNamespace));
    }

    /**
     * @dataProvider classNamesFromDifferentNamespaceProvider
     */
    public function testCannotBeUsedInDifferentNamespace(
        ClassName $className,
        ClassName $classFromDifferentNamespace
    ): void {
        $visibility = new ProtectedVisibility($className->toString());

        self::assertFalse($visibility->canBeUsedBy($classFromDifferentNamespace));
    }

    /**
     * @dataProvider classNameAndClassNameFromItsNestedNamespaceProvider
     */
    public function testCanBeUsedInsideNestedNamespace(ClassName $className, ClassName $classFromInsideNamespace): void
    {
        $visibility = new ProtectedVisibility($className->toString());

        self::assertTrue($visibility->canBeUsedBy($classFromInsideNamespace));
    }
}
