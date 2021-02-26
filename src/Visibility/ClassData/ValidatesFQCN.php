<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassData;


use Lukasz93P\ClassVisibility\VisibilityException;

trait ValidatesFQCN
{
    /**
     * @throws VisibilityException
     */
    private static function validate(string $value): void
    {
        if (empty(trim($value))) {
            throw VisibilityException::wrongClassNameProvided($value);
        }

        $isValidName = preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/', $value);

        if (!$isValidName) {
            throw VisibilityException::wrongClassNameProvided($value);
        }
    }
}