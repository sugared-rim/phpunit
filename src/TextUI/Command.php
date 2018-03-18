<?php

namespace SugaredRim\PHPUnit\TextUI;

use PHPUnit\Runner\Version;

if (version_compare(Version::id(), '7.0', '>=')) {
    require_once __DIR__.'/../../Command7.php';
} else {
    require_once __DIR__.'/../../Command6.php';
}
