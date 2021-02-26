<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\ValueObjects;


interface ValueObject
{
    public function equals(ValueObject $other): bool;
}