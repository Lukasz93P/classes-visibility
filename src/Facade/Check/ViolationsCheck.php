<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Facade\Check;


use Lukasz93P\ClassVisibility\Facade\ClassesVisibility;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassToCheckRepository;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;

class ViolationsCheck implements ClassesVisibility
{
    private function __construct(private ClassToCheckRepository $repository)
    {
    }

    public static function create(ClassToCheckRepository $repository): static
    {
        return new self($repository);
    }

    public function getViolations(NamespacePath $namespace): VisibilityViolations
    {
        $violations = VisibilityViolations::create();

        foreach ($this->repository->getAllClasses($namespace) as $classToCheck) {
            $violations = $violations->merge($classToCheck->checkVisibilityViolation());
        }

        return $violations;
    }
}