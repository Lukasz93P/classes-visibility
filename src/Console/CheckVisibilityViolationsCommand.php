<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Console;

use Lukasz93P\ClassVisibility\Console\Presenter\ViolationsPresenter;
use Lukasz93P\ClassVisibility\Facade\ClassesVisibility;
use Lukasz93P\ClassVisibility\Visibility\ClassData\NamespacePath;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckVisibilityViolationsCommand extends Command
{
    public const ARGUMENT_NAMESPACES = 'namespaces';

    private const NAME = 'check';

    private const HELP_MESSAGE =
        <<<MES
            Command checks all classes from given namespaces for visibility violations.
            Returns 1 along with message for each violations when there are some violations.
            0 otherwise.
        MES;

    private function __construct(private ViolationsPresenter $presenter, private ClassesVisibility $classesVisibility)
    {
        parent::__construct(self::NAME);
    }

    public static function create(ViolationsPresenter $presenter, ClassesVisibility $classesVisibility): self
    {
        return new self($presenter, $classesVisibility);
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addArgument(self::ARGUMENT_NAMESPACES, InputArgument::REQUIRED, 'Namespace to check')
            ->setDescription('Check visibility violations in given coma-separated namespaces')
            ->setHelp(self::HELP_MESSAGE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespacesToCheck = array_map(
            fn(string $namespace) => NamespacePath::create($namespace),
            explode(',', $input->getArgument(self::ARGUMENT_NAMESPACES))
        );

        $visibilityViolations = $this->classesVisibility->getViolations($namespacesToCheck);

        if ($visibilityViolations->isEmpty()) {
            $output->writeln("<info>No violations found.</info>");

            return self::SUCCESS;
        }

        $output->write($this->presenter->present($visibilityViolations));

        return self::FAILURE;
    }
}