<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Violations;


use Lukasz93P\ClassVisibility\Repository\Violations\ClassFinder\ClassFinder;
use Lukasz93P\ClassVisibility\Repository\Violations\UseStatements\UseStatements;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassToCheckRepository;
use Lukasz93P\ClassVisibility\Visibility\ClassToCheck\ClassWithUseDependencies;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;

class ClassWithUseDependenciesRepository implements ClassToCheckRepository
{
    private function __construct(
        private ClassFinder $finder,
        private VisibilityRepository $visibilityRepository,
        private UseStatements $useStatements
    ) {
    }

    public static function create(
        ClassFinder $finder,
        VisibilityRepository $visibilityRepository,
        UseStatements $useStatementsRepository
    ): static {
        return new self($finder, $visibilityRepository, $useStatementsRepository);
    }

    public function getAllClasses(NamespacePath $namespaceToGetClassesFrom): array
    {
        return array_map(
            fn(string $className) => $this->createClassWithUseDependencies($className),
            $this->finder->getAllClassesNames($namespaceToGetClassesFrom)
        );
    }

    private function createClassWithUseDependencies(string $className): ClassWithUseDependencies
    {
        $newClassName = ClassName::create($className);

        return ClassWithUseDependencies::create(
            $newClassName,
            $this->visibilityRepository,
            $this->useStatements->getClassesUsedBy($newClassName)
        );
    }
}