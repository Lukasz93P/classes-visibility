<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Console\Presenter;


use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

interface ViolationsPresenter
{
    public function present(VisibilityViolations $violations): string;
}