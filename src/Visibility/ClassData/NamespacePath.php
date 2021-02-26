<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassData;


use Lukasz93P\ClassVisibility\ValueObjects\ValueObject;
use Lukasz93P\ClassVisibility\VisibilityException;

class NamespacePath implements ValueObject
{
    use ValidatesFQCN;

    private function __construct(private string $path)
    {
    }

    /**
     * @throws VisibilityException
     */
    public static function create(string $path): static
    {
        self::validate($path);

        return new self($path);
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
        return $this->path;
    }
}