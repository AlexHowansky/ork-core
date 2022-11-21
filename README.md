# Ork Core

Core bits of the Ork toolset.

[![Latest Version](https://img.shields.io/packagist/v/ork/core.svg)](https://packagist.org/packages/ork/core)
[![PHP](https://img.shields.io/packagist/php-v/ork/core.svg)](https://php.net)
[![License](https://img.shields.io/github/license/AlexHowansky/ork-core.svg)](https://github.com/AlexHowansky/ork-core/blob/master/LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-8-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Workflow Status](https://img.shields.io/github/workflow/status/AlexHowansky/ork-core/tests?&label=tests)](https://github.com/AlexHowansky/ork-core/actions/workflows/tests.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/AlexHowansky/ork-core)](https://app.codecov.io/gh/AlexHowansky/ork-core)

## Requirements

* PHP 8.0
* PHP 8.1

## Installation

### Via command line

```bash
composer require ork/core
```

### Via composer.json

```json
"require": {
    "ork/core": "*"
},
```

## Documentation

See the [docs](docs/Index.md) directory.

## Development

### Coding Style Validation

Coding style validation is performed by [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
A composer alias is provided to run the validation.

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
