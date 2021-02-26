<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Repository\Violations;


use Lukasz93P\ClassVisibility\Repository\Violations\ClassFinder\ClassFinder;
use Lukasz93P\ClassVisibility\Repository\Violations\ClassWithUseDependenciesRepository;
use Lukasz93P\ClassVisibility\Repository\Violations\UseStatements\UseStatements;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClassWithUseDependenciesRepositoryTest extends TestCase
{
    private MockObject|ClassFinder $classFinderMock;

    private ClassWithUseDependenciesRepository $systemUnderTest;

    public function namespacePathAndFoundClassNamesProvider(): array
    {
        return [
            [NamespacePath::create('Test\Abc\Classes'), ['Test\Abc\Classes\AClass', 'Test\Abc\Classes\BClass']],
            [NamespacePath::create('Application\Commands'), ['Application\Commands\MyClass']],
            [
                NamespacePath::create('SomeApp\Domain\Payments'),
                ['SomeApp\Domain\Payments\Invoice', 'SomeApp\Domain\Payments\Payers\SomePayerClass'],
            ],
        ];
    }

    /**
     * @dataProvider namespacePathAndFoundClassNamesProvider
     */
    public function testShouldReturnEmptyArrayWhenFinderNotFoundAnyClass(NamespacePath $namespace): void
    {
        $this->classFinderMock->method('getAllClassesNames')->with($namespace)->willReturn([]);

        self::assertEmpty($this->systemUnderTest->getAllClasses($namespace));
    }

    /**
     * @dataProvider namespacePathAndFoundClassNamesProvider
     */
    public function testShouldReturnClassesFoundByFinder(NamespacePath $namespace, array $foundClassNames): void
    {
        $this->classFinderMock->method('getAllClassesNames')->with($namespace)->willReturn($foundClassNames);

        self::assertCount(count($foundClassNames), $this->systemUnderTest->getAllClasses($namespace));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->classFinderMock = $this->getMockBuilder(ClassFinder::class)->getMock();

        $this->systemUnderTest = ClassWithUseDependenciesRepository::create(
            $this->classFinderMock,
            $this->getMockBuilder(VisibilityRepository::class)->getMock(),
            $this->getMockBuilder(UseStatements::class)->getMock()
        );
    }
}