<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\Violations;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\VisibilityException;
use PHPUnit\Framework\TestCase;

class ViolationTest extends TestCase
{
    public function classNamesProvider(): array
    {
        return [
            [ClassName::create('Test\Abc\Test'), ClassName::create('SomeNamespace\Cdg\Class')],
            [ClassName::create('Abc\Test\Domain'), ClassName::create('SomeNamespace\Cdg\Class')],
            [ClassName::create('Some\Thing\ClaSS'), ClassName::create('My\Test\Namespace\TEST')],
        ];
    }

    /**
     * @dataProvider classNamesProvider
     */
    public function testTwoInstancesCreatedWithTwoSameClassesShouldBeEqual(ClassName $className, ClassName $other): void
    {
        self::assertTrue(
            VisibilityViolation::create($className, $other)->equals(
                VisibilityViolation::create($className, $other)
            )
        );
    }

    /**
     * @dataProvider classNamesProvider
     */
    public function testTwoInstancesCreatedWithTwoSameClassesButWithTheirDifferentRolesShouldNotBeEqual(
        ClassName $className,
        ClassName $other
    ): void {
        self::assertFalse(
            VisibilityViolation::create($className, $other)->equals(
                VisibilityViolation::create($other, $className)
            )
        );
    }

    /**
     * @dataProvider classNamesProvider
     */
    public function testCannotBeCreatedWithTwoSameClassNames(ClassName $className,): void
    {
        $this->expectException(VisibilityException::class);

        VisibilityViolation::create($className, $className);
    }
}