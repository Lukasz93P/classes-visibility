<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Facade;


use Lukasz93P\ClassVisibility\Facade\ClassesVisibilityFactory;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use PHPUnit\Framework\TestCase;

class ClassesVisibilityTest extends TestCase
{
    public function namespaceProvider(): array
    {
        return [
            [NamespacePath::create('Some\Test\Namespace')],
            [NamespacePath::create('Some\Test')],
            [NamespacePath::create('Domain\Payments\Invoices')],
        ];
    }

    /**
     * @dataProvider namespaceProvider
     */
    public function testShouldExecuteWithoutErrors(NamespacePath $namespace): void
    {
        $this->expectNotToPerformAssertions();

        ClassesVisibilityFactory::create()->getViolations([$namespace]);
    }
}