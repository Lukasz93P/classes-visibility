<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\PublicVisibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\Visibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;
use Lukasz93P\ClassVisibility\VisibilityException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class ReflectionVisibilityRepository implements VisibilityRepository
{
    private function __construct()
    {
    }

    public static function create(): static
    {
        return new static();
    }

    public function getVisibility(ClassName $className): Visibility
    {
        try {
            $class = new ReflectionClass($className->toString());
        } catch (ReflectionException $reflectionException) {
            throw VisibilityException::fromReason($reflectionException);
        }

        $visibilityAttributes = $class->getAttributes(Visibility::class, ReflectionAttribute::IS_INSTANCEOF);

        /** @var Visibility $visibility */
        $visibility = $visibilityAttributes
            ? $visibilityAttributes[0]->newInstance()
            : new PublicVisibility($className->toString());

        return $visibility;
    }
}