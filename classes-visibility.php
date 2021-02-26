<?php

require __DIR__ . '/vendor/autoload.php';

use Lukasz93P\ClassVisibility\Console\CheckVisibilityViolationsCommand;
use Lukasz93P\ClassVisibility\Console\Presenter\SimpleViolationsPresenter;
use Lukasz93P\ClassVisibility\Facade\ClassesVisibilityFactory;
use Symfony\Component\Console\Application;

$application = new Application();

$application
    ->add(
        CheckVisibilityViolationsCommand::create(
            SimpleViolationsPresenter::create(),
            ClassesVisibilityFactory::create()
        )
    );

$application->run();
