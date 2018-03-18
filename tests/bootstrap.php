<?php

namespace Schnittstabil\ComposerExtra;

require __DIR__.'/../vendor/autoload.php';

/*
 * PHPUnit 5/6
 */
if (!class_exists(\PHPUnit\Framework\TestCase::class)) {
    class_alias(\PHPUnit_Framework_TestCase::class, \PHPUnit\Framework\TestCase::class);
}

if (!class_exists(\PHPUnit\Framework\TestResult::class)) {
    class_alias(\PHPUnit_Framework_TestResult::class, \PHPUnit\Framework\TestResult::class);
}

if (!class_exists(\PHPUnit\TextUI\Command::class)) {
    class_alias(\PHPUnit_TextUI_Command::class, \PHPUnit\TextUI\Command::class);
}

if (!class_exists(\PHPUnit\TextUI\TestRunner::class)) {
    class_alias(\PHPUnit_TextUI_TestRunner::class, \PHPUnit\TextUI\TestRunner::class);
}

if (!class_exists(\PHPUnit\Runner\Version::class)) {
    class_alias(\PHPUnit_Runner_Version::class, \PHPUnit\Runner\Version::class);
}
