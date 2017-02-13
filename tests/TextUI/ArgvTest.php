<?php

namespace SugaredRim\PHPUnit\TextUI;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ArgvTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyArgvShouldReturnEmptyArgv()
    {
        $argv = new Argv(new \stdClass());
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy'], $sut);
    }

    public function testSugaredDebugOptionShouldBeRendered()
    {
        $config = new \stdClass();
        $config->sugared = new \stdClass();
        $config->sugared->debug = true;

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--sugared-debug'], $sut);
    }

    public function testBootstrapOptionShouldBeRendered()
    {
        $config = new \stdClass();
        $config->bootstrap = uniqid();

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--bootstrap='.$config->bootstrap], $sut);
    }

    public function testColorsOptionShouldBeRendered()
    {
        $config = new \stdClass();
        $config->colors = true;

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--colors'], $sut);
    }

    public function testTestsOptionShouldBeRendered()
    {
        $config = new \stdClass();
        $config->tests = 'tests';

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', 'tests'], $sut);
    }

    public function testTestsArgumentShouldBeRendered()
    {
        $argv = new Argv(new \stdClass());
        $sut = $argv(['./dummy', 'tests']);

        $this->assertEquals(['./dummy', 'tests'], $sut);
    }

    public function testTestsOptionShouldBeRewritable()
    {
        $config = new \stdClass();
        $config->tests = 'tests';

        $argv = new Argv($config);
        $sut = $argv(['./dummy', 'newTests']);

        $this->assertEquals(['./dummy', 'newTests'], $sut);
    }

    public function testCoverageCloverOptionShouldBeRendered()
    {
        $config = new \stdClass();
        $config->src = 'src';
        $config->coverage = new \stdClass();
        $config->coverage->clover = 'build/logs/clover.xml';

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals([
            './dummy',
            '--coverage-clover=build/logs/clover.xml',
            '--whitelist=src',
        ], $sut);
    }

    public function testCoverageOptionsCanBeDisabled()
    {
        $config = new \stdClass();
        $config->coverage = new \stdClass();
        $config->coverage->clover = false;

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy'], $sut);
    }

    public function testCoverageWithoutSrcShouldThrowError()
    {
        $config = new \stdClass();
        $config->coverage = new \stdClass();
        $config->coverage->clover = 'build/logs/clover.xml';

        $argv = new Argv($config);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/src/');
        $argv(['./dummy']);
    }

    public function testCoverageWithInvalidSrcShouldThrowError()
    {
        $config = new \stdClass();
        $config->src = uniqid();
        $config->coverage = new \stdClass();
        $config->coverage->clover = 'build/logs/clover.xml';

        $argv = new Argv($config);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/directory could not be found/');
        $argv(['./dummy']);
    }

    public function testCoverageTextShowUncoveredFilesOptionResultShouldBeRendered()
    {
        $config = new \stdClass();
        $config->src = 'src';
        $config->coverage = new \stdClass();
        $config->coverage->text = 'php://stdout';
        $config->sugared = new \stdClass();
        $config->sugared->{'coverage-text-show-uncovered-files'} = true;

        $argv = new Argv($config);
        $sut = $argv(['./dummy']);

        $this->assertEquals([
            './dummy',
            '--coverage-text=php://stdout',
            '--whitelist=src',
            '--sugared-coverage-text-show-uncovered-files',
        ], $sut);
    }
}
