<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassData;


use Lukasz93P\ClassVisibility\ValueObjects\ValueObject;
use Lukasz93P\ClassVisibility\VisibilityException;

class ClassName implements ValueObject
{
    use ValidatesFQCN;

    private function __construct(private string $name)
    {
    }

    /**
     * @throws VisibilityException
     */
    public static function create(string $name): static
    {
        self::validate($name);

        return new self($name);
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->toString() === $other->toString();
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function isClassWithinNamespace(ClassName $other): bool
    {
        return str_contains($other->namespace(), $this->namespace());
    }

    public function namespace(): string
    {
        $lastSlashPosition = strrpos($this->name, '\\') ?: 0;

        return substr($this->name, 0, $lastSlashPosition);
    }

    public function isClassInExactNamespace(ClassName $other): bool
    {
        return $this->namespace() === $other->namespace();
    }
}
