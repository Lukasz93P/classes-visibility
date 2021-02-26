<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\ClassToCheck;


use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\Visibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClassWithUseDependenciesTest extends TestCase
{
    private ClassName $className;

    private MockObject|VisibilityRepository $visibilityRepository;

    private MockObject|Visibility $visibilityMock;

    public function classNamesProviders(): array
    {
        return [
            [ClassName::create('Test\Abc\Name')],
            [ClassName::create('Namespace\Domain\ClassName')],
            [ClassName::create('Application\Commands\CreateSomething')],
        ];
    }

    /**
     * @dataProvider classNamesProviders
     */
    public function testShouldNotDetectViolationIfNotViolatesVisibility(ClassName $usedClass): void
    {
        $this->visibilityRepository->method('getVisibility')->with($usedClass)->willReturn($this->visibilityMock);

        $this->visibilityMock->method('canBeUsedBy')->with($this->className)->willReturn(true);

        $systemUnderTest = ClassWithUseDependencies::create(
            $this->className,
            $this->visibilityRepository,
            UniqueValueObjectsCollection::create([$usedClass])
        );

        self::assertEmpty($systemUnderTest->checkVisibilityViolation()->toArray());
    }

    /**
     * @dataProvider classNamesProviders
     */
    public function testShouldDetectViolationIfViolatesVisibility(ClassName $usedClass): void
    {
        $this->visibilityRepository->method('getVisibility')->with($usedClass)->willReturn($this->visibilityMock);

        $this->visibilityMock->method('canBeUsedBy')->with($this->className)->willReturn(false);

        $systemUnderTest = ClassWithUseDependencies::create(
            $this->className,
            $this->visibilityRepository,
            UniqueValueObjectsCollection::create([$usedClass])
        );

        self::assertNotEmpty($systemUnderTest->checkVisibilityViolation()->toArray());
    }

    /**
     * @dataProvider classNamesProviders
     */
    public function testShouldDetectManyViolationIfViolatesVisibility(): void
    {
        $classWhichShouldNotBeVisible = ClassName::create('Namespace\ClassName\Private');
        $classWhichShouldBeVisible = ClassName::create('Namespace\ClassName\Public');
        $anotherClassWhichShouldNotBeVisible = ClassName::create('Namespace\ClassName\AnotherPrivate');

        $this->visibilityRepository
            ->method('getVisibility')
            ->withConsecutive(
                [$classWhichShouldNotBeVisible],
                [$classWhichShouldBeVisible],
                [$anotherClassWhichShouldNotBeVisible]
            )
            ->willReturn($this->visibilityMock);

        $this->visibilityMock->method('canBeUsedBy')->willReturnOnConsecutiveCalls(false, true, false);

        $systemUnderTest = ClassWithUseDependencies::create(
            $this->className,
            $this->visibilityRepository,
            UniqueValueObjectsCollection::create(
                [$classWhichShouldNotBeVisible, $classWhichShouldBeVisible, $anotherClassWhichShouldNotBeVisible]
            )
        );

        $violations = $systemUnderTest->checkVisibilityViolation()->toArray();

        self::assertCount(2, $violations);
        self::assertTrue(
            $violations[0]->equals(VisibilityViolation::create($classWhichShouldNotBeVisible, $this->className))
        );
        self::assertTrue(
            $violations[1]->equals(VisibilityViolation::create($anotherClassWhichShouldNotBeVisible, $this->className))
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->className = ClassName::create('Some\Test\Class');

        $this->visibilityRepository = $this->getMockBuilder(VisibilityRepository::class)->getMock();
        $this->visibilityMock = $this->getMockBuilder(Visibility::class)->getMock();
    }

}