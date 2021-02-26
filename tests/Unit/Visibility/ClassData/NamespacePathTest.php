<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\ClassData;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\VisibilityException;
use PHPUnit\Framework\TestCase;

class NamespacePathTest extends TestCase
{
    public function wrongPathProvider(): array
    {
        return [
            [''],
            [' '],
            ['SomeDir\*\Test'],
            ['25\Test\MyClass'],
        ];
    }

    public function pathProvider(): array
    {
        return [
            ['SomeNamespace\Test\Abc\Class', 'SomeNamespace\Test\Abc'],
            ['Domain\Element\SomeElement', 'Domain\Element'],
            ['Class\Some\Test', 'Class\Some'],
        ];
    }

    /**
     * @dataProvider wrongPathProvider
     */
    public function testShouldNotAllowToBeCreateWithWrongPath(string $wrongPath): void
    {
        $this->expectException(VisibilityException::class);

        NamespacePath::create($wrongPath);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testCreateWithSamePathShouldBeEqual(string $path): void
    {
        self::assertTrue(ClassName::create($path)->equals(ClassName::create($path)));
    }

    /**
     * @dataProvider pathProvider
     */
    public function testCreateWithDifferentPathShouldNotBeEqual(string $path, string $differentPath): void
    {
        self::assertFalse(NamespacePath::create($path)->equals(NamespacePath::create($differentPath)));
        self::assertFalse(NamespacePath::create($differentPath)->equals(NamespacePath::create($path)));
    }
}