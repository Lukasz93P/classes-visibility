<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Tests\Integration\Repository\Visibilities\VisibilityExamples;

use Lukasz93P\ClassVisibility\Visibility\Visibilities\PrivateVisibility;

#[PrivateVisibility(self::class)]
class ClassWithPrivateVisibility
{

}