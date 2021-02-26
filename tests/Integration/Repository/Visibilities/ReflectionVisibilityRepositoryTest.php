<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Visibilities;


use Lukasz93P\ClassVisibility\Repository\Visibilities\ReflectionVisibilityRepository;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Visibilities\VisibilityExamples\ClassWithNoVisibilityDefined;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Visibilities\VisibilityExamples\ClassWithPrivateVisibility;
use Lukasz93P\ClassVisibility\Tests\Integration\Repository\Visibilities\VisibilityExamples\ClassWithProtectedVisibility;
use Lukasz93P\ClassVisibility\Visibility\ClassData\ClassName;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\PrivateVisibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\ProtectedVisibility;
use Lukasz93P\ClassVisibility\Visibility\Visibilities\PublicVisibility;
use PHPUnit\Framework\TestCase;

class ReflectionVisibilityRepositoryTest extends TestCase
{
    private ReflectionVisibilityRepository $systemUnderTest;

    public function testShouldReturnPublicVisibilityWhenClassHasNoAttribute(): void
    {
        self::assertInstanceOf(
            PublicVisibility::class,
            $this->systemUnderTest->getVisibility(ClassName::create(ClassWithNoVisibilityDefined::class))
        );
    }

    public function testShouldReturnProtectedVisibilityWhenClassHasThisAttribute(): void
    {
        self::assertInstanceOf(
            ProtectedVisibility::class,
            $this->systemUnderTest->getVisibility(ClassName::create(ClassWithProtectedVisibility::class))
        );
    }

    public function testShouldReturnPrivateVisibilityWhenClassHasThisAttribute(): void
    {
        self::assertInstanceOf(
            PrivateVisibility::class,
            $this->systemUnderTest->getVisibility(ClassName::create(ClassWithPrivateVisibility::class))
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->systemUnderTest = ReflectionVisibilityRepository::create();
    }
}