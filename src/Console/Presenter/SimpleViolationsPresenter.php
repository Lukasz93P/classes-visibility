<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Console\Presenter;


use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

class SimpleViolationsPresenter implements ViolationsPresenter
{
    private function __construct()
    {
    }

    public static function create(): static
    {
        return new static();
    }

    public function present(VisibilityViolations $violations): string
    {
        $violationsMessage = '';

        foreach ($violations->toArray() as $violation) {
            $violationsMessage .= $this->generateViolationMessage($violation);
        }

        return $violationsMessage;
    }

    private function generateViolationMessage(VisibilityViolation $violation): string
    {
        return "{$violation->getClassWhichViolatedVisibility()} cannot use {$violation->getClassWhichShouldNotBeVisible()}"
            . PHP_EOL;
    }
}