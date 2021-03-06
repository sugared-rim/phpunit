<?php

namespace SugaredRim\PHPUnit\TextUI;

use function Schnittstabil\Get\getValue;
use Gamez\Psr\Log\TestLogger;
use JohnKary\PHPUnit\Listener\SpeedTrapListener;

class CommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestLogger
     */
    protected $logger;

    protected function setUp()
    {
        $this->logger = new TestLogger();
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

                $runner = $this->getMockBuilder(\PHPUnit\TextUI\TestRunner::class)
                    ->setConstructorArgs([$arguments['loader']])
                    ->setMethods(['doRun'])
                    ->getMock();

                $runner->method('doRun')->willReturn(new \PHPUnit\Framework\TestResult());

                return $runner;
            }));

        return $command;
    }

    public function testSugaredDebugShouldOutputArguments()
    {
        $argv = [
            '-',
            '--sugared-debug',
            'SugaredRim\PHPUnit\Fixtures\StackTest',
            'tests/Fixtures/StackTest.php',
        ];

        $sut = $this->buildCommand();
        $sut->run($argv, false);

        $log = implode(PHP_EOL, $this->logger->getRecords());

        $this->assertRegexp('#Arguments:#', $log);
        $this->assertRegexp('#"--sugared-debug"#', $log);
        $this->assertRegexp('#"tests\\\\/Fixtures\\\\/StackTest.php"#', $log);
        $this->assertRegexp('#Config:#', $log);
        $this->assertRegexp('#"--sugared-coverage-text-show-uncovered-files"#', $log);
        $this->assertRegexp('#Parsed arguments:#', $log);
        $this->assertRegexp('#"sugaredDebug": true#', $log);
        $this->assertNotRegExp('/No tests executed/', $log);
    }

    public function testSugaredNamespaceShouldAlterConfig()
    {
        $argv = [
            '-',
            '--sugared-namespace', 'sugared-rim/phpunit test-namespace',
            'SugaredRim\PHPUnit\Fixtures\StackTest',
            'tests/Fixtures/StackTest.php',
        ];

        $sut = $this->buildCommand();
        $sut->handleArguments($argv);
        $reflector = new \ReflectionMethod(get_class($sut), 'getConfig');
        $reflector->setAccessible(true);
        $config = $reflector->invoke($sut);

        $this->assertEquals(42, getValue('sugared.unicorns', $config));
    }

    public function testSpeedTrapListenerExample()
    {
        if (!class_exists(SpeedTrapListener::class)) {
            $this->markTestSkipped('SpeedTrapListener is not available.');

            return;
        }

        $argv = [
            '-',
            '--sugared-namespace', 'sugared-rim/phpunit test-speedtrap',
            'SugaredRim\PHPUnit\Fixtures\StackTest',
            'tests/Fixtures/StackTest.php',
        ];

        $sut = $this->buildCommand();
        $sut->handleArguments($argv);
        $reflector = new \ReflectionProperty(get_class($sut), 'arguments');
        $reflector->setAccessible(true);
        $arguments = $reflector->getValue($sut);

        $this->assertInstanceOf(SpeedTrapListener::class, getValue('listeners.0', $arguments));
    }
}
