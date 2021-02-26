<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\ClassData;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\VisibilityException;
use PHPUnit\Framework\TestCase;

class ClassNameTest extends TestCase
{
    public function classNameAndNamespaceProvider(): array
    {
        return [
            ['SomeNamespace\Test\Abc\Class', 'SomeNamespace\Test\Abc'],
            ['Domain\Element\SomeElement', 'Domain\Element'],
            ['Class\Some\Test', 'Class\Some'],
        ];
    }

    public function wrongClassNameProvider(): array
    {
        return [
            [''],
            [' '],
            ['SomeDir\*\Test'],
            ['25\Test\MyClass'],
        ];
    }

    /**
     * @dataProvider classNameAndNamespaceProvider
     */
    public function testShouldReturnNamespace(string $fqn, string $expectedNamespace): void
    {
        $className = ClassName::create($fqn);

        self::assertEquals($expectedNamespace, $className->namespace());
    }

    /**
     * @dataProvider wrongClassNameProvider
     */
    public function testShouldNotAllowToBeCreateWithWrongClassName(string $wrongClassName): void
    {
        $this->expectException(VisibilityException::class);

        ClassName::create($wrongClassName);
    }

    /**
     * @dataProvider classNameAndNamespaceProvider
     */
    public function testShouldHasProperName(string $className): void
    {
        self::assertEquals($className, ClassName::create($className)->toString());
    }

    /**
     * @dataProvider classNameAndNamespaceProvider
     */
    public function testCreateWithSameClassNamesShouldBeEqual(string $className): void
    {
        self::assertTrue(ClassName::create($className)->equals(ClassName::create($className)));
    }

    /**
     * @dataProvider classNameAndNamespaceProvider
     */
    public function testCreateWithDifferentClassNamesShouldNotBeEqual(string $className, string $otherClassName): void
    {
        self::assertFalse(ClassName::create($className)->equals(ClassName::create($otherClassName)));
        self::assertFalse(ClassName::create($otherClassName)->equals(ClassName::create($className)));
    }

    public function classesFromSameHigherNamespaceProvider(): array
    {
        return [
            [
                ClassName::create('Test\Abc\MyClass'),
                ClassName::create('Test\Abc\SomeTest\Class'),
                true,
            ],
            [
                ClassName::create('Test\Abc\MyClass'),
                ClassName::create('Test\Abc\Class'),
                true,
            ],
            [
                ClassName::create('Test\Abc\MyClass'),
                ClassName::create('Test\Nested\Namespace\ClassName'),
                false,
            ],
            [
                ClassName::create('Test\Abc\Nested\MyClass'),
                ClassName::create('Test\Abc\Nested\ANother\Class'),
                true,
            ],
            [
                ClassName::create('Test\Abc\Nested\MyClass'),
                ClassName::create('Test\Abc\Cba'),
                false,
            ],
            [
                ClassName::create('Domain\Element\SomeElement'),
                ClassName::create('Test\Abc\Cba'),
                false,
            ],
            [
                ClassName::create('Domain\Element\SomeElement'),
                ClassName::create('Test\Domain'),
                false,
            ],
        ];
    }

    public function classesFromExactSameNamespaceProvider(): array
    {
        return [
            [
                ClassName::create('Test\Abc\MyClass'),
                ClassName::create('Test\Abc\SomeClass'),
                true,
            ],
            [
                ClassName::create('Test\Abc\MyClass'),
                ClassName::create('Test\Bcd\Class'),
                false,
            ],
            [
                ClassName::create('Test\Abc\Nested\MyClass'),
                ClassName::create('Test\Abc\MyClass'),
                false,
            ],
            [
                ClassName::create('Test\Abc\Nested\MyClass'),
                ClassName::create('Test\Abc\Cba\Class'),
                false,
            ],
            [
                ClassName::create('Domain\Element\SomeElement'),
                ClassName::create('Test\Abc\Cba'),
                false,
            ],
            [
                ClassName::create('Domain\Element\SomeElement'),
                ClassName::create('Test\Domain'),
                false,
            ],
            [
                ClassName::create('Domain\Element\SomeElement'),
                ClassName::create('Domain\Element\SomeNamespace\Test'),
                false,
            ],
        ];
    }

    /**
     * @dataProvider classesFromSameHigherNamespaceProvider
     */
    public function testShouldCheckIfClassNameIsWithinNamespace(
        ClassName $className,
        ClassName $anotherClass,
        bool $shouldBeWithin
    ): void {
        self::assertEquals($shouldBeWithin, $className->isClassWithinNamespace($anotherClass));
    }

    /**
     * @dataProvider classesFromExactSameNamespaceProvider
     */
    public function testShouldCheckIfClassNameIsInExactNamespace(
        ClassName $className,
        ClassName $anotherClass,
        bool $shouldBeWithin
    ): void {
        self::assertEquals($shouldBeWithin, $className->isClassInExactNamespace($anotherClass));
    }
}
