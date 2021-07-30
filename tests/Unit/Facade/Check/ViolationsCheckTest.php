<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Facade\Check;


use Lukasz93P\ClassVisibility\Facade\Check\ViolationsCheck;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassToCheck;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassToCheckRepository;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ViolationsCheckTest extends TestCase
{
    private MockObject|ClassToCheckRepository $repositoryMock;

    private ViolationsCheck $systemUnderTest;

    public function namespaceClassesToCheckMocksAndViolationsQuantityProvider(): array
    {
        return [
            [
                NamespacePath::create('Test\Some\Class'),
                [
                    $this->createClassToCheckForVisibilityDependenciesMockWithViolations(2),
                    $this->createClassToCheckForVisibilityDependenciesMockWithViolations(3),
                    $this->createClassToCheckForVisibilityDependenciesMockWithViolations(0),
                ],
                5,
            ],
            [
                NamespacePath::create('Application\Some\Class'),
                [
                    $this->createClassToCheckForVisibilityDependenciesMockWithViolations(12),
                    $this->createClassToCheckForVisibilityDependenciesMockWithViolations(5),
                ],
                17,
            ],
            [NamespacePath::create('Domain\Payments\Invoice'), [], 0],
        ];
    }

    private function createClassToCheckForVisibilityDependenciesMockWithViolations(int $violationsQuantity
    ): MockObject|ClassToCheck {
        $classToCheckForVisibilityViolationsMock = $this
            ->getMockBuilder(ClassToCheck::class)
            ->getMock();

        $violationsMock = $this->getMockBuilder(VisibilityViolations::class)->disableOriginalConstructor()->getMock();

        $violations = [];
        for ($i = 0; $i < $violationsQuantity; $i++) {
            $violations[] = $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock();
        }
        $violationsMock->method('toArray')->willReturn($violations);

        $classToCheckForVisibilityViolationsMock->method('checkVisibilityViolation')->willReturn($violationsMock);

        return $classToCheckForVisibilityViolationsMock;
    }

    /**
     * @dataProvider namespaceClassesToCheckMocksAndViolationsQuantityProvider
     */
    public function testShouldReturnEmptyViolationsWhenThereAreNoClassesToCheck(NamespacePath $namespace): void
    {
        $this->repositoryMock->method('getAllClasses')->with($namespace)->willReturn([]);

        self::assertEmpty($this->systemUnderTest->getViolations([$namespace])->toArray());
    }

    /**
     * @dataProvider namespaceClassesToCheckMocksAndViolationsQuantityProvider
     */
    public function testShouldReturnViolationsReturnedFromAllCheckedClasses(
        NamespacePath $namespace,
        array $classesToCheck,
        int $allViolationsQuantity
    ): void {
        $this->repositoryMock->method('getAllClasses')->with($namespace)->willReturn($classesToCheck);

        self::assertCount($allViolationsQuantity, $this->systemUnderTest->getViolations([$namespace])->toArray());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder(ClassToCheckRepository::class)->getMock();

        $this->systemUnderTest = ViolationsCheck::create($this->repositoryMock);
    }
}