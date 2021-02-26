<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Repository\Visibilities;


use Lukasz93P\ClassVisibility\Repository\Visibilities\InMemoryCacheProxyVisibilityRepository;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\Visibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InMemoryCacheProxyClassVisibilityRepositoryTest extends TestCase
{
    private MockObject|VisibilityRepository $classVisibilityRepository;

    private InMemoryCacheProxyVisibilityRepository $systemUnderTest;

    public function visibilityAndClassNameProvider(): array
    {
        return [
            [$this->getMockBuilder(Visibility::class)->getMock(), ClassName::create('Test\ABC')],
            [$this->getMockBuilder(Visibility::class)->getMock(), ClassName::create('Some\CLass')],
            [$this->getMockBuilder(Visibility::class)->getMock(), ClassName::create('My\Namespace\Class')],
        ];
    }

    /**
     * @dataProvider visibilityAndClassNameProvider
     */
    public function testShouldReturnVisibilityFromDecoratedRepository(
        Visibility $visibility,
        ClassName $className
    ): void {
        $this->classVisibilityRepository
            ->expects(self::once())
            ->method('getVisibility')
            ->with($className)
            ->willReturn($visibility);

        self::assertEquals($visibility, $this->systemUnderTest->getVisibility($className));
    }

    /**
     * @dataProvider visibilityAndClassNameProvider
     */
    public function testShouldReturnCachedVisibilityAtSecondCall(
        Visibility $visibility,
        ClassName $className
    ): void {
        $this->classVisibilityRepository
            ->expects(self::once())
            ->method('getVisibility')
            ->with($className)
            ->willReturn($visibility);

        self::assertEquals($visibility, $this->systemUnderTest->getVisibility($className));
        self::assertEquals($visibility, $this->systemUnderTest->getVisibility($className));
    }

    /**
     * @dataProvider visibilityAndClassNameProvider
     */
    public function testShouldReturnVisibilityFromDecoratedRepositoryWhenClassNameNotQueriedPreviousle(
        Visibility $visibility,
        ClassName $className
    ): void {
        $newClassName = ClassName::create('Some\Another\NotCached\Class');

        $newVisibility = $this->getMockBuilder(Visibility::class)->getMock();

        $this->classVisibilityRepository
            ->expects(self::exactly(2))
            ->method('getVisibility')
            ->withConsecutive([$className], [$newClassName])
            ->willReturnOnConsecutiveCalls($visibility, $newVisibility);

        $this->systemUnderTest->getVisibility($className);

        self::assertEquals($newVisibility, $this->systemUnderTest->getVisibility($newClassName));
        $this->systemUnderTest->getVisibility($newClassName);
        $this->systemUnderTest->getVisibility($className);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->classVisibilityRepository = $this->getMockBuilder(VisibilityRepository::class)->getMock();

        $this->systemUnderTest = InMemoryCacheProxyVisibilityRepository::create($this->classVisibilityRepository);
    }
}