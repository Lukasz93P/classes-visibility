<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Console;


use Lukasz93P\ClassVisibility\Console\CheckVisibilityViolationsCommand;
use Lukasz93P\ClassVisibility\Console\Presenter\ViolationsPresenter;
use Lukasz93P\ClassVisibility\Facade\ClassesVisibility;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CheckVisibilityViolationsCommandTest extends TestCase
{
    private MockObject|ViolationsPresenter $presenterMock;

    private MockObject|ClassesVisibility $classesVisibilityMock;

    private CommandTester $commandTester;

    public function namespaceAndPresenterMessageProvider(): array
    {
        return [
            ['Test\Abc\Test', 'Some message' . PHP_EOL . ' something'],
            ['Test\ABCA\Test', 'afsd f sdf sg dsg '],
            ['My\namespace\My\Class\name', 'Test message' . PHP_EOL . 'ABS' . PHP_EOL],
        ];
    }

    /**
     * @dataProvider namespaceAndPresenterMessageProvider
     */
    public function testShouldReturnSuccessExitCodeWhenNoViolationsFound(string $namespace): void
    {
        $this->classesVisibilityMock
            ->method('getViolations')
            ->with(self::callback(static fn(NamespacePath $namespacePath) => $namespacePath->toString() === $namespace))
            ->willReturn(VisibilityViolations::create());

        self::assertEquals(
            Command::SUCCESS,
            $this->commandTester->execute([CheckVisibilityViolationsCommand::ARGUMENT_NAMESPACE => $namespace])
        );
    }

    /**
     * @dataProvider namespaceAndPresenterMessageProvider
     */
    public function testShouldReturnFailureExitCodeWhenViolationsFound(string $namespace): void
    {
        $this->mockNonEmptyViolationsForGivenNamespace($namespace);

        self::assertEquals(
            Command::FAILURE,
            $this->commandTester->execute([CheckVisibilityViolationsCommand::ARGUMENT_NAMESPACE => $namespace])
        );
    }

    private function mockNonEmptyViolationsForGivenNamespace(string $namespace): void
    {
        $nonEmptyVisibilityViolations = VisibilityViolations::create();
        $nonEmptyVisibilityViolations->add(
            $this->getMockBuilder(VisibilityViolation::class)->disableOriginalConstructor()->getMock()
        );

        $this->classesVisibilityMock
            ->method('getViolations')
            ->with(self::callback(static fn(NamespacePath $namespacePath) => $namespacePath->toString() === $namespace))
            ->willReturn($nonEmptyVisibilityViolations);
    }

    /**
     * @dataProvider namespaceAndPresenterMessageProvider
     */
    public function testShouldReturnMessageFromPresenterWhenViolationsFound(
        string $namespace,
        string $presenterMessage
    ): void {
        $this->mockNonEmptyViolationsForGivenNamespace($namespace);

        $this->presenterMock->method('present')->willReturn($presenterMessage);

        $this->commandTester->execute([CheckVisibilityViolationsCommand::ARGUMENT_NAMESPACE => $namespace]);

        $display = $this->commandTester->getDisplay();

        self::assertStringContainsString($presenterMessage, $display);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->presenterMock = $this->getMockBuilder(ViolationsPresenter::class)->getMock();
        $this->classesVisibilityMock = $this->getMockBuilder(ClassesVisibility::class)->getMock();

        $systemUnderTest = CheckVisibilityViolationsCommand::create(
            $this->presenterMock,
            $this->classesVisibilityMock
        );

        $this->commandTester = new CommandTester($systemUnderTest);
    }
}