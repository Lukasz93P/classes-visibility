<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements;


use Lukasz93P\ClassVisibility\Repository\Violations\UseStatements\ReflectionUseStatements;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples\ClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples\FirstClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples\SecondClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Violations\UseStatements\ClassesWithUseStatementsExamples\ThirdClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use PHPUnit\Framework\TestCase;

class ReflectionUseStatementsTest extends TestCase
{
    private ReflectionUseStatements $systemUnderTest;

    public function classWithUseDependenciesProvider(): array
    {
        return [
            [new SecondClassWithUseDependencies()],
            [new ThirdClassWithUseDependencies()],
            [new FirstClassWithUseDependencies()],
        ];
    }

    /**
     * @dataProvider classWithUseDependenciesProvider
     */
    public function testShouldReturnClassesUsesByGivenClass(ClassWithUseDependencies $classWithUseDependencies): void
    {
        $usedClasses = $this->systemUnderTest
            ->getClassesUsedBy(ClassName::create($classWithUseDependencies::class))
            ->toArray();

        /** @var ClassName $usedClass */
        foreach ($usedClasses as $usedClass) {
            self::assertContains($usedClass->toString(), $classWithUseDependencies->getUsedClasses());
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->systemUnderTest = ReflectionUseStatements::create();
    }
}