<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\Violations;


use Lukasz93P\ClassVisibility\ValueObjects\ValueObject;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\VisibilityException;

class VisibilityViolation implements ValueObject
{
    private function __construct(
        private ClassName $classWhichShouldNotBeVisible,
        private ClassName $classWhichViolatedVisibility
    ) {
    }

    /**
     * @throws VisibilityException
     */
    public static function create(
        ClassName $classWhichShouldNotBeVisible,
        ClassName $classWhichViolatedVisibility
    ): static {
        if ($classWhichShouldNotBeVisible->equals($classWhichViolatedVisibility)) {
            throw VisibilityException::classCannotViolatesVisibilityOfItself($classWhichViolatedVisibility->toString());
        }

        return new self($classWhichShouldNotBeVisible, $classWhichViolatedVisibility);
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->classWhichViolatedVisibility->equals($other->classWhichViolatedVisibility)
            && $this->classWhichShouldNotBeVisible->equals($other->classWhichShouldNotBeVisible);
    }

    public function getClassWhichShouldNotBeVisible(): string
    {
        return $this->classWhichShouldNotBeVisible->toString();
    }

    public function getClassWhichViolatedVisibility(): string
    {
        return $this->classWhichViolatedVisibility->toString();
    }
}