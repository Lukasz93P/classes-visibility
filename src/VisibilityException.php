<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility;


use RuntimeException;
use Throwable;

class VisibilityException extends RuntimeException
{
    protected function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $previous ? $previous->getCode() : 0, $previous);
    }

    public static function wrongClassNameProvided(string $providedClassName): static
    {
        return new static("Not valid class name provided: [{$providedClassName}]");
    }

    public static function classCannotViolatesVisibilityOfItself(string $className): static
    {
        return new static("Class cannot violates visibility of itself: [{$className}]");
    }

    public static function fromReason(Throwable $reason): static
    {
        return new static($reason->getMessage(), $reason);
    }
}
