<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\Violations;


use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VisibilityViolationsTest extends TestCase
{
    public function visibilityViolationsProvider(): array
    {
        return [
            [
                $this->getMockBuilder(VisibilityViolations::class)->disableOriginalConstructor()->getMock(),
                [
                    $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
                    $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
                ],
            ],
            [
                $this->getMockBuilder(VisibilityViolations::class)->disableOriginalConstructor()->getMock(),
                [],
            ],
            [
                $this->getMockBuilder(VisibilityViolations::class)->disableOriginalConstructor()->getMock(),
                [
                    $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
                    $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
                    $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
                ],
            ],
        ];
    }

    /**
     * @dataProvider visibilityViolationsProvider
     */
    public function testShouldMergeWithOther(VisibilityViolations|MockObject $other, array $violations): void
    {
        $other->method('toArray')->willReturn($violations);

        $visibilityViolationsCollection = VisibilityViolations::create();

        $visibilityViolationsCollection->add(
            $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
        );

        $visibilityViolationsCollection->add(
            $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock(),
        );

        $visibilityViolationsCollection->merge($other);

        self::assertCount(
            2 + count($other->toArray()),
            $visibilityViolationsCollection->merge($other)->toArray()
        );
    }

    public function testShouldBeEmptyWhenNoViolationsAdded(): void
    {
        self::assertTrue(VisibilityViolations::create()->isEmpty());
    }

    public function testShouldNotBeEmptyWhenViolationsAdded(): void
    {
        $violations = VisibilityViolations::create();
        $violations->add(
            $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock()
        );

        self::assertFalse($violations->isEmpty());
    }
}