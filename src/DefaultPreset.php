<?php

namespace Schnittstabil\Sugared\PHPUnit;

class DefaultPreset
{
    public static function get()
    {
        return [
            'bootstrap' => 'vendor/autoload.php',
            'coverage' => [
                'text' => 'php://stdout',
                'clover' => 'build/logs/clover.xml',
                'html' => 'build/coverage/',
            ],
            'sugared' => [
                'debug' => false,
                'coverage-text-show-uncovered-files' => true,
            ],
            'src' => 'src',
            'tests' => 'tests',
            'colors' => true,
        ];
    }
}
