<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\Visibilities;

use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\PrivateVisibility;

class PrivateVisibilityTest extends VisibilityTest
{
    /**
     * @dataProvider classNamesFromExactNamespaceProvider
     */
    public function testCanBeUsedInExactSameNamespace(
        ClassName $className,
        ClassName $classFromExactSameNamespace
    ): void {
        $visibility = new PrivateVisibility($className->toString());

        self::assertTrue($visibility->canBeUsedBy($classFromExactSameNamespace));
    }

    /**
     * @dataProvider classNamesFromDifferentNamespaceProvider
     */
    public function testCannotBeUsedInDifferentNamespace(
        ClassName $className,
        ClassName $classFromDifferentNamespace
    ): void {
        $visibility = new PrivateVisibility($className->toString());

        self::assertFalse($visibility->canBeUsedBy($classFromDifferentNamespace));
    }

    /**
     * @dataProvider classNameAndClassNameFromItsNestedNamespaceProvider
     */
    public function testCannotBeUsedInsideNestedNamespace(
        ClassName $className,
        ClassName $classFromNestedNamespace
    ): void {
        $visibility = new PrivateVisibility($className->toString());

        self::assertFalse($visibility->canBeUsedBy($classFromNestedNamespace));
    }
}
