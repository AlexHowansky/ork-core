# \Ork\Core\Filter\State\NameToAbbreviation

Converts a state name to its USPS abbreviation.

* [Usage](#usage)
* [Exception on error](#exception-on-error)
* [US territories](#us-territories)

## Usage

```php
use Ork\Core\Filter\State\NameToAbbreviation;

$filter = new NameToAbbreviation();
echo $filter->filter('New York');
```

Output:

```text
NY
```

## Exception on error

By default, an `UnexpectedValueException` exception will be thrown when invalid
input is encountered. To prevent this, and instead return the unprocessed value,
specify the `abortOnInvalidInput` configuration parameter.

```php
use Ork\Core\Filter\State\NameToAbbreviation;

$filter = new NameToAbbreviation(['abortOnInvalidInput' => false]);
echo $filter->filter('this is not a state');
```

Output:

```text
this is not a state
```

## US territories

By default, this class will not convert abbreviations for US territories.
Specify the `includeTerritories` configuration parameter to allow this.

```php
use Ork\Core\Filter\State\NameToAbbreviation;

$filter = new NameToAbbreviation(['includeTerritories' => true]);
echo $filter->filter('Puerto Rico');
```

Output:

```text
PR
```
