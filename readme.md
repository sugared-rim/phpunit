# SugaredPHPUnit [![Build Status](https://travis-ci.org/schnittstabil/sugared-phpunit.svg?branch=master)](https://travis-ci.org/schnittstabil/sugared-phpunit) [![Coverage Status](https://coveralls.io/repos/schnittstabil/sugared-phpunit/badge.svg?branch=master&service=github)](https://coveralls.io/github/schnittstabil/sugared-phpunit?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/schnittstabil/sugared-phpunit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/schnittstabil/sugared-phpunit/?branch=master) [![Code Climate](https://codeclimate.com/github/schnittstabil/sugared-phpunit/badges/gpa.svg)](https://codeclimate.com/github/schnittstabil/sugared-phpunit)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d937d693-2bb7-428b-be4a-817a1fb5d9a0/big.png)](https://insight.sensiolabs.com/projects/d937d693-2bb7-428b-be4a-817a1fb5d9a0)

> PHPUnit sweetened with ease :cherries:

SugaredPHPUnit takes an opinionated view of testing with [PHPUnit](https://phpunit.de), it is preconfigured to get you up and running as quickly as possible.

## Install

```
$ composer require --dev schnittstabil/sugared-phpunit
```

## Usage

Instead of requiring and running `phpunit` use `sugared-phpunit` - that's it, no `phpunit.xml*` needed:

```json
{
    ...
    "require-dev": {
        "schnittstabil/sugared-phpunit": ...
    },
    "scripts": {
        "test": "sugared-phpunit"
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
        "test": "sugared-phpunit"
    },
    "extra": {
        "schnittstabil/sugared-phpunit": {
            "bootstrap": "vendor/autoload.php",
            "coverage": {
                "text": "php://stdout",
                "clover": "build/logs/clover.xml",
                "html": "build/coverage/"
            },
            "src": "src",
            "tests": "tests",
            "colors": true
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
