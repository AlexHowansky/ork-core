# Ork Core

Core bits of the Ork toolset.

[![Latest Stable Version](https://img.shields.io/packagist/v/ork/core.svg?style=flat)](https://packagist.org/packages/ork/core)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-max-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![PHP](https://img.shields.io/packagist/php-v/ork/core.svg?style=flat)](http://php.net)
[![License](https://img.shields.io/github/license/AlexHowansky/ork-core.svg?style=flat)](https://github.com/AlexHowansky/ork-core/blob/master/LICENSE)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/AlexHowansky/ork-core/tests?style=flat&label=workflow)](https://github.com/AlexHowansky/ork-core/actions?query=workflow%3Atests)
[![Travis Build Status](https://img.shields.io/travis/AlexHowansky/ork-core/master.svg?style=flat&label=Travis)](https://secure.travis-ci.org/AlexHowansky/ork-core)

## Requirements

* PHP 7.3

## Installation

### Via command line

```bash
composer require ork/core
```

### Via composer.json

```json
"require": {
    "ork/core": "^1.0.0"
},
```

## Documentation

See the [docs](docs/Index.md) directory.

## Development

### Coding Style Validation

Coding style validation is performed by [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
A compser alias is provided to run the validation.

```bash
composer phpcs
```

### Static Analysis

Static analysis is performed by [PHPStan](https://github.com/phpstan/phpstan).
A composer alias is provided to run the analysis.

```bash
composer phpstan
```

### Unit Testing

Unit testing is performed by [PHPUnit](https://github.com/sebastianbergmann/phpunit).
A composer alias is provided to run the tests.

```bash
composer test
```
