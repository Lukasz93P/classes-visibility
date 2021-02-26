<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Visibility\ClassToCheck;


use Lukasz93P\ClassVisibility\ValueObjects\UniqueValueObjectsCollection;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolation;
use Lukasz93P\ClassVisibility\Visibility\Violations\VisibilityViolations;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;

class ClassWithUseDependencies implements ClassToCheck
{
    private function __construct(
        private ClassName $name,
        private VisibilityRepository $visibilityRepository,
        private UniqueValueObjectsCollection $usedClasses
    ) {
    }

    public static function create(
        ClassName $name,
        VisibilityRepository $visibilityRepository,
        UniqueValueObjectsCollection $usedClasses
    ): static {
        return new self($name, $visibilityRepository, $usedClasses);
    }

    public function checkVisibilityViolation(): VisibilityViolations
    {
        $violations = VisibilityViolations::create();

        /** @var ClassName $usedClass */
        foreach ($this->usedClasses->toArray() as $usedClass) {
            $violations = $this->addViolationIfNecessary($usedClass, $violations);
        }

        return $violations;
    }

    private function addViolationIfNecessary(
        ClassName $usedClass,
        VisibilityViolations $violations
    ): VisibilityViolations {
        if (!$this->visibilityRepository->getVisibility($usedClass)->canBeUsedBy($this->name)) {
            $violations->add(VisibilityViolation::create($usedClass, $this->name));
        }

        return $violations;
    }
}