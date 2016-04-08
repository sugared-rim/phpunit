<?php

namespace Schnittstabil\Sugared\PHPUnit\TextUI;

use function Schnittstabil\Get\getValue;
use Gamez\Psr\Log\TestLoggerTrait;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    use TestLoggerTrait;

    protected $logger;

    protected function setUp()
    {
        $this->logger = $this->getTestLogger();
    }

    protected function buildCommand()
    {
        $command = $this->getMockBuilder(Command::class)
            ->setConstructorArgs([$this->logger])
            ->setMethods(['createRunner'])
            ->getMock();

        $command->method('createRunner')
            ->will($this->returnCallback(function () use ($command) {
                $reflector = new \ReflectionProperty(get_class($command), 'arguments');
                $reflector->setAccessible(true);
                $arguments = $reflector->getValue($command);

                $runner = $this->getMockBuilder(\PHPUnit_TextUI_TestRunner::class)
                    ->setConstructorArgs([$arguments['loader']])
                    ->setMethods(['doRun'])
                    ->getMock();

                $runner->method('doRun')->willReturn(new \PHPUnit_Framework_TestResult());

                return $runner;
            }));

        return $command;
    }

    public function testSugaredDebugShouldOutputArguments()
    {
        $argv = [
            '-',
            '--sugared-debug',
            'Schnittstabil\Sugared\PHPUnit\Fixtures\StackTest',
            'tests/Fixtures/StackTest.php',
        ];

        $sut = $this->buildCommand();
        $sut->run($argv, false);

        $log = implode(PHP_EOL, $this->logger->getRecords());
        $this->assertRegexp('#Arguments:#', $log);
        $this->assertRegexp('#"--sugared-debug"#', $log);
        $this->assertRegexp('#"tests\\\\/Fixtures\\\\/StackTest.php"#', $log);
        $this->assertRegexp('#Parsed arguments:#', $log);
        $this->assertRegexp('#"sugaredDebug": true#', $log);
        $this->assertNotRegExp('/No tests executed/', $log);
    }

    public function testSugaredNamespaceShouldAlterConfig()
    {
        $argv = [
            '-',
            '--sugared-namespace', 'schnittstabil/sugared-phpunit test-namespace',
            'Schnittstabil\Sugared\PHPUnit\Fixtures\StackTest',
            'tests/Fixtures/StackTest.php',
        ];

        $sut = $this->buildCommand();
        $sut->handleArguments($argv);
        $reflector = new \ReflectionMethod(get_class($sut), 'getConfig');
        $reflector->setAccessible(true);
        $config = $reflector->invoke($sut);

        $this->assertEquals(42, getValue('sugared.unicorns', $config));
    }
}
