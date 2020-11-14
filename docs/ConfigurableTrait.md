# \Ork\Core\ConfigurableTrait

`\Ork\Core\ConfigurableTrait` provides a convention for defining and supplying
configuration parameters to objects.

* [Defining an object's configuration](#defining-an-objects-configuration)
* [Setting at object's configuration](#setting-at-objects-configuration)
  * [Implicitly via constructor with array](#implicitly-via-constructor-with-array)
  * [Implicitly via constructor with JSON file](#implicitly-via-constructor-with-json-file)
  * [Explicitly with single element](#explicitly-with-single-element)
  * [Explicitly with multiple elements](#explicitly-with-multiple-elements)
  * [Explicitly with JSON file](#explicitly-with-json-file)
* [Getting an object's configuration](#getting-an-objects-configuration)
  * [Getting a single configuration value](#getting-a-single-configuration-value)
  * [Getting all the configuration values](#getting-all-the-configuration-values)
* [Setter Filtering](#setter-filtering)

## Defining an object's configuration

Classes which use this trait must define a protected array named `$config`,
which contains (as keys) the names of the configuration values and optionally
(as values) a default value. For example:

```php
class Foo
{

    use \Ork\Core\ConfigurableTrait;

    protected $config = [
        'foo' => null,
        'bar' => 123,
    ];

}
```

## Setting at object's configuration

Objects which implement the trait may be configured in any of the following manners:

### Implicitly via constructor with array

```php
$config = [
    'foo' => 'thing',
    'bar' => 'some other thing',
];
$foo = new \Foo($config);
```

### Implicitly via constructor with JSON file

```json
{
    "foo": "thing",
    "bar": "some other thing"
}
```

```php
$foo = new \Foo('/path/to/config.json');
```

### Explicitly with single element

```php
$foo = new \Foo();
$foo->setConfig('foo', 'thing');
$foo->setConfig('bar', 'some other thing');
```

### Explicitly with multiple elements

```php
$config = [
    'foo' => 'thing',
    'bar' => 'some other thing',
];
$foo = new \Foo();
$foo->setConfigs($config);
```

### Explicitly with JSON file

```php
$foo = new \Foo();
$foo->loadConfig('/path/to/config.json');
```

Note all setters utilize a fluent interface, so calls can be stacked:

```php
$foo = new \Foo();
$foo
    ->setConfigs([...])
    ->loadConfig('/path/to/config.json')
    ->setConfig($name1, $value2)
    ->setConfig($name2, $value2);
```

## Getting an object's configuration

### Getting a single configuration value

```php
public function bar()
{
    $fooConfigValue = $this->getConfig('foo');
}
```

### Getting all the configuration values

```php
public function bar()
{
    foreach ($this->getConfigs() as $name => $value) {
        // ...
    }
}
```

## Setter filtering

You may optionally create methods to pre-process values before they are set. This
is accomplished by creating a method named `filterConfig*` where `*` is the
camel-cased name of the configuration attribute. This method should take one
argument (the value to filter) and return one value (the filtered value.) If the
filter method decides to reject a value, it should throw a DomainException. For
example, if the object has a method named filterConfigFoo(), then a call to
`$obj->setConfig('foo', $value)` will result in `foo` being set to the value
returned by `$obj->filterConfigFoo($value)`.

```php
class Foo
{

    use \Ork\Core\ConfigurableTrait;

    protected $config = [
        'foo' => null,
    ];

    protected function filterConfigFoo($value)
    {
        return strtolower(trim($value));
    }

}

$foo = new \Foo();
$foo->setConfig('foo', '   TESTING   ');
echo $foo->getConfig('foo');
```

Output:

```text
testing
```
