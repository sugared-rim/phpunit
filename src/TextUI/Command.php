<?php

namespace Schnittstabil\Sugared\PHPUnit\TextUI;

use function Schnittstabil\Get\getValue;

use Psr\Log\LoggerInterface;
use fool\echolog\Echolog;
use Schnittstabil\ComposerExtra\ComposerExtra;

class Command extends \PHPUnit_TextUI_Command
{
    protected $argvRendererClass = Argv::class;
    protected $namespace = 'schnittstabil/sugared-phpunit';
    protected $defaultConfig = [
        'presets' => ['Schnittstabil\\Sugared\\PHPUnit\\DefaultPreset::get'],
        'argv' => [],
    ];
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function __construct(LoggerInterface $logger = null)
    {
        // @codeCoverageIgnoreStart
        if ($logger === null) {
            $logger = new Echolog();
        }
        // @codeCoverageIgnoreEnd

        $this->logger = $logger;

        $this->longOptions['sugared-coverage-text-show-uncovered-files']
            = 'sugaredCoverageTextShowUncoveredFilesHandler';
        $this->longOptions['sugared-debug'] = 'sugaredDebugHandler';
        $this->longOptions['sugared-namespace='] = 'sugaredNamespaceHandler';
    }

    protected function logDebug($title, $value)
    {
        $this->logger->debug($title.': '.json_encode($value, JSON_PRETTY_PRINT));
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
     * {@inheritdoc}
     */
    public function handleArguments(array $argv)
    {
        parent::handleArguments($argv);

        if (getValue('sugaredDebug', $this->arguments, false)) {
            $this->logDebug('Parsed arguments', $this->arguments);
        }
    }

    protected function getConfig()
    {
        return (new ComposerExtra(
            $this->namespace,
            $this->defaultConfig,
            'presets'
        ))->get();
    }

    protected function preprocessArgv(array $argv)
    {
        $argvRenderer = new $this->argvRendererClass($this->getConfig());

        return $argvRenderer($argv);
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

        parent::run($argv, $exit);
    }
}
