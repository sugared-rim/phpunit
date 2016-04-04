<?php

namespace Schnittstabil\Sugared\PHPUnit\TextUI;

use function Schnittstabil\Get\getValue;

class Argv
{
    protected $config;

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    protected function renderWhitelist($argv)
    {
        $src = getValue('src', $this->config, false);

        if (empty($src)) {
            throw new \InvalidArgumentException(
                sprintf('"src" option is not configured, code coverage is not possible.', $src)
            );
        }

        if (!is_dir($src)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" directory could not be found.', $src)
            );
        }

        $argv[] = '--whitelist='.$src;

        return $argv;
    }

    protected function renderCoverage($argv)
    {
        $coverage = getValue('coverage', $this->config, false);

        if (empty($coverage)) {
            return $argv;
        }


        $includesText = false;
        $withCoverage = false;

        foreach ($coverage as $k => $v) {
            if (!empty($v)) {
                $argv[] = '--coverage-'.$k.'='.$v;
                $includesText = $includesText || $k === 'text';
                $withCoverage = true;
            }
        }

        if ($withCoverage) {
            $argv = $this->renderWhitelist($argv);
        }

        if ($includesText && getValue('sugared.coverage-text-show-uncovered-files', $this->config)) {
            $argv[] = '--sugared-coverage-text-show-uncovered-files';
        }

        return $argv;
    }

    /**
     * Render argv array.
     *
     * @param array $argv
     *
     * @return array
     */
    public function __invoke(array $argv)
    {
        $script = array_shift($argv);

        $argv = $this->renderCoverage($argv);

        if (getValue('sugared.debug', $this->config)) {
            $argv[] = '--sugared-debug';
        }

        if (getValue('colors', $this->config)) {
            $argv[] = '--colors';
        }

        $positionalArgs = array_filter(
            $argv,
            function ($arg) {
                return substr($arg, 0, 1) !== '-';
            }
        );

        if (empty($positionalArgs)) {
            $tests = getValue('tests', $this->config, false);
            if ($tests) {
                $argv[] = $tests;
            }
        }

        array_unshift($argv, $script);

        return $argv;
    }
}
