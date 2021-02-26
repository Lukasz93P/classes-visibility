<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\Visibility\Visibilities;

use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use PHPUnit\Framework\TestCase;

abstract class VisibilityTest extends TestCase
{
    public function classNamesFromExactNamespaceProvider(): array
    {
        return [
            [ClassName::create('Test\Domain\Elements\Test'), ClassName::create('Test\Domain\Elements\Element')],
            [
                ClassName::create('Application\Commands\Command'),
                ClassName::create('Application\Commands\CreateCommand'),
            ],
        ];
    }

    public function classNamesFromDifferentNamespaceProvider(): array
    {
        return [
            [ClassName::create('Test\Domain\Elements\Test'), ClassName::create('Test\Domain\Types\Element')],
            [ClassName::create('Application\Commands\Class'), ClassName::create('Domain\Invoices\Payment')],
        ];
    }

    public function classNameAndClassNameFromItsNestedNamespaceProvider(): array
    {
        return [
            [
                ClassName::create('Test\Domain\Types\SomeAnotherNamespace'),
                ClassName::create('Test\Domain\Types\Element\Test'),
            ],
            [
                ClassName::create('Domain\Invoices\Payers\Sum'),
                ClassName::create('Domain\Invoices\Payers\Something\abc\ClassName'),
            ],
        ];
    }
}
