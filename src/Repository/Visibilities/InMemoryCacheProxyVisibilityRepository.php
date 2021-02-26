<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Visibilities;


use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\Visibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\VisibilityRepository;

class InMemoryCacheProxyVisibilityRepository implements VisibilityRepository
{
    /**
     * @var Visibility[]
     */
    private array $cachedVisibilities = [];

    private function __construct(private VisibilityRepository $repository)
    {
    }

    public static function create(VisibilityRepository $repository): static
    {
        return new self($repository);
    }

    public function getVisibility(ClassName $className): Visibility
    {
        $visibility = $this->cachedVisibilities[$className->toString()] ?? $this->repository->getVisibility($className);

        return $this->cachedVisibilities[$className->toString()] = $visibility;
    }
}