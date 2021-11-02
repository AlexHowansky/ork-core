# \Ork\Core\Filter\State\AbbreviationToName

Converts a USPS state abbreviation to its name.

* [Usage](#usage)
* [Exception on error](#exception-on-error)
* [US territories](#us-territories)

## Usage

```php
use Ork\Core\Filter\State\AbbreviationToName;

$filter = new AbbreviationToName();
echo $filter->filter('NY');
```

Output:

```text
New York
```

## Exception on error

By default, an `UnexpectedValueException` exception will be thrown when invalid
input is encountered. To prevent this, and instead return the unprocessed value,
specify the `abortOnInvalidInput` configuration parameter.

```php
use Ork\Core\Filter\State\AbbreviationToName;

$filter = new AbbreviationToName(['abortOnInvalidInput' => false]);
echo $filter->filter('this is not a state abbreviation');
```

Output:

```text
this is not a state abbreviation
```

## US territories

By default, this class will not convert names for US territories. Specify the
`includeTerritories` configuration parameter to allow this.

```php
use Ork\Core\Filter\State\AbbreviationToName;

$filter = new AbbreviationToName(['includeTerritories' => true]);
echo $filter->filter('PR');
```

Output:

```text
Puerto Rico
```
