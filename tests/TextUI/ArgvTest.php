<?php

namespace Schnittstabil\Sugared\PHPUnit\TextUI;

use VladaHejda\AssertException;

class ArgvTest extends \PHPUnit_Framework_TestCase
{
    use AssertException;

    public function testEmptyArgvShouldReturnEmptyArgv()
    {
        $argv = new Argv();
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy'], $sut);
    }

    public function testSugaredDebugOptionShouldBeRendered()
    {
        $argv = new Argv([
            'sugared' => [
                'debug' => true,
            ],
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--sugared-debug'], $sut);
    }

    public function testBootstrapOptionShouldBeRendered()
    {
        $rnd = uniqid();
        $argv = new Argv([
            'bootstrap' => $rnd,
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--bootstrap='.$rnd], $sut);
    }

    public function testColorsOptionShouldBeRendered()
    {
        $argv = new Argv([
            'colors' => true,
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', '--colors'], $sut);
    }

    public function testTestsOptionShouldBeRendered()
    {
        $argv = new Argv([
            'tests' => 'tests',
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy', 'tests'], $sut);
    }

    public function testTestsArgumentShouldBeRendered()
    {
        $argv = new Argv();
        $sut = $argv(['./dummy', 'tests']);

        $this->assertEquals(['./dummy', 'tests'], $sut);
    }

    public function testTestsOptionShouldBeRewritable()
    {
        $argv = new Argv([
            'tests' => 'tests',
        ]);
        $sut = $argv(['./dummy', 'newTests']);

        $this->assertEquals(['./dummy', 'newTests'], $sut);
    }

    public function testCoverageCloverOptionShouldBeRendered()
    {
        $argv = new Argv([
            'coverage' => [
                'clover' => 'build/logs/clover.xml',
            ],
            'src' => 'src',
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals([
            './dummy',
            '--coverage-clover=build/logs/clover.xml',
            '--whitelist=src',
        ], $sut);
    }

    public function testCoverageOptionsCanBeDisabled()
    {
        $argv = new Argv([
            'coverage' => [
                'clover' => false,
            ],
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals(['./dummy'], $sut);
    }

    public function testCoverageWithoutSrcShouldThrowError()
    {
        $argv = new Argv([
            'coverage' => [
                'clover' => 'build/logs/clover.xml',
            ],
        ]);

        $this->assertException(function () use ($argv) {
            $sut = $argv(['./dummy']);
        }, \InvalidArgumentException::class, null, 'src');
    }

    public function testCoverageWithInvalidSrcShouldThrowError()
    {
        $argv = new Argv([
            'coverage' => [
                'clover' => 'build/logs/clover.xml',
            ],
            'src' => uniqid(),
        ]);

        $this->assertException(function () use ($argv) {
            $sut = $argv(['./dummy']);
        }, \InvalidArgumentException::class, null, 'directory could not be found');
    }

    public function testCoverageTextShowUncoveredFilesOptionResultShouldBeRendered()
    {
        $argv = new Argv([
            'coverage' => [
                'text' => 'php://stdout',
            ],
            'sugared' => [
                'coverage-text-show-uncovered-files' => true,
            ],
            'src' => 'src',
        ]);
        $sut = $argv(['./dummy']);

        $this->assertEquals([
            './dummy',
            '--coverage-text=php://stdout',
            '--whitelist=src',
            '--sugared-coverage-text-show-uncovered-files',
        ], $sut);
    }
}
