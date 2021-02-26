<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Facade;


use Lukasz93P\ClassVisibility\Facade\Check\ViolationsCheck;
use Lukasz93P\ClassVisibility\Repository\Violations\ClassFinder\ExternalLibraryClassFinder;
use Lukasz93P\ClassVisibility\Repository\Violations\ClassWithUseDependenciesRepository;
use Lukasz93P\ClassVisibility\Repository\Violations\UseStatements\ReflectionUseStatements;
use Lukasz93P\ClassVisibility\Repository\Visibilities\InMemoryCacheProxyVisibilityRepository;
use Lukasz93P\ClassVisibility\Repository\Visibilities\ReflectionVisibilityRepository;

class ClassesVisibilityFactory
{
    public static function create(): ClassesVisibility
    {
        return ViolationsCheck::create(
            ClassWithUseDependenciesRepository::create(
                ExternalLibraryClassFinder::create(),
                InMemoryCacheProxyVisibilityRepository::create(ReflectionVisibilityRepository::create()),
                ReflectionUseStatements::create()
            )
        );
    }
}