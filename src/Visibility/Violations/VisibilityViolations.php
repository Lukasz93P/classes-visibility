<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Violations;


use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;

class VisibilityViolations
{
    private function __construct(private UniqueValueObjectsCollection $violations)
    {
    }

    public static function create(): static
    {
        return new self(UniqueValueObjectsCollection::create([]));
    }

    public function add(VisibilityViolation $violation): void
    {
        $this->violations->add($violation);
    }

    public function merge(VisibilityViolations $other): VisibilityViolations
    {
        return new self(UniqueValueObjectsCollection::create(array_merge($other->toArray(), $this->toArray())));
    }

    /**
     * @return VisibilityViolation[]
     */
    public function toArray(): array
    {
        return $this->violations->toArray();
    }

    public function isEmpty(): bool
    {
        return empty($this->violations->toArray());
    }
}