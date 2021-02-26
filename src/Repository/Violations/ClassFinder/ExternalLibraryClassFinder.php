<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Violations\ClassFinder;


use HaydenPierce\ClassFinder\ClassFinder as ExternalLibrary;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;

class ExternalLibraryClassFinder implements ClassFinder
{
    private function __construct()
    {

    }

    public static function create(): static
    {
        return new static();
    }

    public function getAllClassesNames(NamespacePath $namespace): array
    {
        return ExternalLibrary::getClassesInNamespace($namespace->toString(), ExternalLibrary::RECURSIVE_MODE);
    }
}