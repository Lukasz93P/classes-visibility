<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\ValueObjects;


class UniqueValueObjectsCollection
{
    /**
     * @param ValueObject[] $valueObjects
     */
    private array $valueObjects = [];

    private function __construct()
    {
    }

    /**
     * @param ValueObject[] $valueObjects
     */
    public static function create(array $valueObjects): static
    {
        $instance = new self();
        foreach ($valueObjects as $valueObject) {
            $instance->add($valueObject);
        }

        return $instance;
    }

    public function add(ValueObject $valueObject): void
    {
        foreach ($this->valueObjects as $containedValueObject) {
            if ($containedValueObject->equals($valueObject)) {
                return;
            }
        }

        $this->valueObjects[] = $valueObject;
    }

    /**
     * @return ValueObject[]
     */
    public function toArray(): array
    {
        return $this->valueObjects;
    }

    public function count(): int
    {
        return count($this->valueObjects);
    }
}