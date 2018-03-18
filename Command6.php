<?php

namespace SugaredRim\PHPUnit\TextUI;

use Schnittstabil\Get\Get;

class Command extends BaseCommand
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function handleArguments(array $argv)
    {
        parent::handleArguments($argv);

        if (Get::value('sugaredDebug', $this->arguments, false)) {
            $this->logDebug('Parsed arguments', $this->arguments);
        }

        $this->addListeners();
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function run(array $argv, $exit = true)
    {
        $argv = $this->preprocessArgv($argv);

        if (in_array('--sugared-debug', $argv)) {
            $this->logDebug('Arguments', $argv);
        }

        return parent::run($argv, $exit);
    }
}
