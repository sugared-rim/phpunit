<?php

namespace SugaredRim\PHPUnit;

use JohnKary\PHPUnit\Listener\SpeedTrapListener;

class DefaultPreset
{
    public static function get()
    {
        $config = new \stdClass();

        $config->bootstrap = 'vendor/autoload.php';
        $config->src = 'src';
        $config->tests = 'tests';
        $config->colors = true;

        $config->coverage = new \stdClass();
        $config->coverage->text = 'php://stdout';
        $config->coverage->clover = 'build/logs/clover.xml';
        $config->coverage->html = 'build/coverage-phpunit/';

        $config->sugared = new \stdClass();
        $config->sugared->debug = false;
        $config->sugared->{'coverage-text-show-uncovered-files'} = true;

        $config->sugared->listeners = [];

        $speedtrap = new \stdClass();
        $speedtrap->class = SpeedTrapListener::class;
        $options = new \stdClass();
        $options->slowThreshold = 500;
        $options->reportLength = 10;
        $speedtrap->arguments = [$options];

        $config->sugared->listeners[] = $speedtrap;

        return $config;
    }
}
