# \Ork\Core\String\Template

A simple template renderer.

* [Specifying a template](#specifying-a-template)
* [Rendering the template](#rendering-the-template)
* [Specifying custom delimiters](#specifying-custom-delimiters)

## Specifying a template

The template string is specified via the `template` configuration parameter. It
should contain delimited tags as placeholders for to be replaced.

```php
use Ork\Core\String\Template;

$template = new Template(['template' => "Name: {{name}}\nDOB: {{dob}}"]);
```

## Rendering the template

The template is rendered by passing an associative array of replacement values
to the `render()` method.

```php
$template->render([
    'name' => 'Foo Bar',
    'dob' => '1970-01-01',
]);
```

## Specifying custom delimiters

By default, template tags are delimited by `{{` and `}}`. This can be changed
via the `delimiterStart` and `delimiterEnd` configuration parameters.

```php
use Ork\Core\String\Template;

$template = new Template([
    'delimiterStart' => '<',
    'delimiterEnd' => '>',
    'template' => "Name: <name>\nDOB: <dob>",
]);
$template->render([
    'name' => 'Foo Bar',
    'dob' => '1970-01-01',
]);
```
