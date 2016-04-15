<?php

namespace Schnittstabil\Sugared\PHPUnit;

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
        $config->coverage->html = 'build/coverage/';

        $config->sugared = new \stdClass();
        $config->sugared->debug = false;
        $config->sugared->{'coverage-text-show-uncovered-files'} = true;

        return $config;
    }
}
