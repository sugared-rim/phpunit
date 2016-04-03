<?php

namespace Schnittstabil\Sugared\PHPUnit\TextUI;

use Schnittstabil\Get;

class Argv
{
    protected $config;

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function renderWhitelist($argv)
    {
        $src = Get::value('src', $this->config, false);

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

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function renderCoverage($argv)
    {
        $coverage = Get::value('coverage', $this->config, false);

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

        if ($includesText && Get::value('sugared.coverage-text-show-uncovered-files', $this->config)) {
            $argv[] = '--sugared-coverage-text-show-uncovered-files';
        }

        return $argv;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __invoke(array $argv)
    {
        $script = array_shift($argv);

        $argv = $this->renderCoverage($argv);

        if (Get::value('sugared.debug', $this->config)) {
            $argv[] = '--sugared-debug';
        }

        if (Get::value('colors', $this->config)) {
            $argv[] = '--colors';
        }

        $positionalArgs = array_filter($argv, function ($arg) { return substr($arg, 0, 1) !== '-'; });

        if (empty($positionalArgs)) {
            $tests = Get::value('tests', $this->config, false);
            if ($tests) {
                $argv[] = $tests;
            }
        }

        array_unshift($argv, $script);

        return $argv;
    }
}
