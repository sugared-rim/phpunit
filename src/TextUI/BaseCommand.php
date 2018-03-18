<?php

namespace SugaredRim\PHPUnit\TextUI;

use Psr\Log\LoggerInterface;
use Schnittstabil\ComposerExtra\ComposerExtra;
use Schnittstabil\Get\Get;

class BaseCommand extends \PHPUnit\TextUI\Command
{
    protected $argvRendererClass = Argv::class;
    protected $namespace = 'sugared-rim/phpunit';
    protected $defaultConfig;
    protected $logger;
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->defaultConfig = new \stdClass();
        $this->defaultConfig->presets = ['SugaredRim\\PHPUnit\\DefaultPreset::get'];

        $this->logger = $logger;

        $this->longOptions['sugared-coverage-text-show-uncovered-files']
            = 'sugaredCoverageTextShowUncoveredFilesHandler';
        $this->longOptions['sugared-debug'] = 'sugaredDebugHandler';
        $this->longOptions['sugared-namespace='] = 'sugaredNamespaceHandler';
    }

    protected function logDebug($title, $value)
    {
        if ($this->logger !== null) {
            $this->logger->debug($title.': '.json_encode($value, JSON_PRETTY_PRINT));
        }
    }

    protected function sugaredCoverageTextShowUncoveredFilesHandler()
    {
        $this->arguments['coverageTextShowUncoveredFiles'] = true;
    }

    protected function sugaredDebugHandler()
    {
        $this->arguments['sugaredDebug'] = true;
    }

    protected function sugaredNamespaceHandler($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function addListeners()
    {
        foreach ((array) $this->getConfig('sugared.listeners', []) as $listener) {
            $class = Get::value('class', $listener);
            $listenerClass = new \ReflectionClass($class);
            $arguments = json_decode(json_encode(Get::value('arguments', $listener, [])), true);

            $this->arguments['listeners'][] = $listenerClass->newInstanceArgs($arguments);
        }
    }

    protected function getConfig($path = null, $default = null)
    {
        if ($this->config === null) {
            $this->config = new ComposerExtra(
                $this->namespace,
                $this->defaultConfig,
                'presets'
            );
        }

        if ($path === null) {
            $path = array();
        }

        return $this->config->get($path, $default);
    }

    protected function preprocessArgv(array $argv)
    {
        $config = $this->getConfig();

        if (in_array('--sugared-debug', $argv)) {
            $this->logDebug('Config', $config);
        }

        $argvRenderer = new $this->argvRendererClass($config);

        return $argvRenderer($argv);
    }
}
