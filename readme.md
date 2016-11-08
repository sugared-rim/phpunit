# SugaredRim\PHPUnit [![Build Status](https://travis-ci.org/sugared-rim/phpunit.svg?branch=master)](https://travis-ci.org/sugared-rim/phpunit) [![Coverage Status](https://coveralls.io/repos/sugared-rim/phpunit/badge.svg?branch=master&service=github)](https://coveralls.io/github/sugared-rim/phpunit?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sugared-rim/phpunit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sugared-rim/phpunit/?branch=master) [![Code Climate](https://codeclimate.com/github/sugared-rim/phpunit/badges/gpa.svg)](https://codeclimate.com/github/sugared-rim/phpunit)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4db73f60-e740-424d-a0b8-7ac1571164b5/big.png)](https://insight.sensiolabs.com/projects/4db73f60-e740-424d-a0b8-7ac1571164b5)

> PHPUnit sweetened with ease :cherries:

SugaredRim\PHPUnit takes an opinionated view of testing with [PHPUnit](https://phpunit.de), it is preconfigured to get you up and running as quickly as possible.

## Install

```
$ composer require --dev sugared-rim/phpunit
```

## Usage

Instead of requiring and running `phpunit` use `sugared-rim-phpunit` - that's it, no `phpunit.xml*` needed:

```json
{
    ...
    "require-dev": {
        "sugared-rim/phpunit": ...
    },
    "scripts": {
        "test": "sugared-rim-phpunit"
    }
}
```

## Configuration

You may overwrite some options by putting it in your `composer.json`.

Some of the default settings:
```json
{
    ...
    "scripts": {
        "test": "sugared-rim-phpunit"
    },
    "extra": {
        "sugared-rim/phpunit": {
            "bootstrap": "vendor/autoload.php",
            "coverage": {
                "text": "php://stdout",
                "clover": "build/logs/clover.xml",
                "html": "build/coverage-phpunit/"
            },
            "src": "src",
            "tests": "tests",
            "colors": true,
            "sugared": {
                "listeners": [
                    {
                        "class": "JohnKary\\PHPUnit\\Listener\\SpeedTrapListener",
                        "arguments": [{"slowThreshold": 500, "reportLength": 10}]
                    }
                ]
            }
        }
    }
}
```

### src

The source directory.

### tests

The tests directory.

### coverage

See [Command-Line Options](https://phpunit.de/manual/current/en/textui.html#textui.clioptions) for details.

## License

MIT © [Michael Mayer](http://schnittstabil.de)
