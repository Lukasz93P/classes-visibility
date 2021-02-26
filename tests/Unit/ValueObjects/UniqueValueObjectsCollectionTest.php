<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Unit\ValueObjects;


use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;
use Lukasz93P\ClassVisibility\ValueObjects\ValueObject;
use PHPUnit\Framework\TestCase;

class UniqueValueObjectsCollectionTest extends TestCase
{
    public function valueObjectsQuantityProvider(): array
    {
        return [
            [1],
            [0],
            [42],
            [24],
            [5],
        ];
    }

    /**
     * @dataProvider valueObjectsQuantityProvider
     */
    public function testShouldHasProperCount(int $valueObjectsQuantity): void
    {
        $collection = UniqueValueObjectsCollection::create([]);

        for ($i = 0; $i < $valueObjectsQuantity; $i++) {
            $collection->add($this->getMockBuilder(ValueObject::class)->getMock());
        }

        self::assertEquals($valueObjectsQuantity, $collection->count());
    }

    /**
     * @dataProvider valueObjectsQuantityProvider
     */
    public function testShouldConvertToArray(int $valueObjectsQuantity): void
    {
        $valueObject = $this->getMockBuilder(ValueObject::class)->getMock();
        $collection = UniqueValueObjectsCollection::create([$valueObject]);

        $valueObjects = [$valueObject];
        for ($i = 0; $i < $valueObjectsQuantity; $i++) {
            $valueObject = $this->getMockBuilder(ValueObject::class)->getMock();
            $valueObjects[] = $valueObject;
            $collection->add($valueObject);
        }

        self::assertEquals($valueObjects, $collection->toArray());
    }

    public function testShouldNotAllowToAddDuplicates(): void
    {
        $valueObject = $this->getMockBuilder(ValueObject::class)->getMock();
        $valueObject->method('equals')->with($valueObject)->willReturn(true);

        $collection = UniqueValueObjectsCollection::create([$valueObject]);
        $collection->add($valueObject);

        self::assertEquals(1, $collection->count());
    }

    public function testShouldNotAllowToBeCreatedWithDuplicates(): void
    {
        $valueObject = $this->getMockBuilder(ValueObject::class)->getMock();
        $valueObject->method('equals')->with($valueObject)->willReturn(true);

        $collection = UniqueValueObjectsCollection::create([$valueObject, $valueObject]);

        self::assertEquals(1, $collection->count());
    }
}