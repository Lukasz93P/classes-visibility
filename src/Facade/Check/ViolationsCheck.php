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

    /**
     * @param NamespacePath[] $namespaces
     */
    public function getViolations(array $namespaces): VisibilityViolations
    {
        $violations = VisibilityViolations::create();

        foreach ($namespaces as $namespace) {
            foreach ($this->repository->getAllClasses($namespace) as $classToCheck) {
                $violations = $violations->merge($classToCheck->checkVisibilityViolation());
            }
        }

        return $violations;
    }
}