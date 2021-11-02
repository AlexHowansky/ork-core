# \Ork\Core\Image\Gravatar

A simple Gravatar URI helper.

* [Getting a Gravatar URI](#getting-a-gravatar-uri)
* [Specifying the image size](#specifying-the-image-size)
* [Specifying a default URI](#specifying-a-default-uri)

## Getting a Gravatar URI

```php
use Ork\Core\Image\Gravatar;

$gravatar = new Gravatar(['email' => 'foo@bar.com']);
echo $gravatar->getUri();
```

Output:

```text
http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8.jpg
```

The Gravatar object also may be cast to a string or simply used in a string
context:

```php
use Ork\Core\Image\Gravatar;

echo new Gravatar(['email' => 'foo@bar.com']);
```

## Specifying the image size

Unless otherwise specified, the generated URI will serve Gravatar's default
image size. To override this, specify the `size` configuration parameter.

```php
use Ork\Core\Image\Gravatar;

echo new Gravatar([
    'email' => 'foo@bar.com',
    'size' => 128,
]);
```

## Specifying a default URI

If the provided email address has no associated image, Gravatar will serve a
default image. To override this and serve your own preferred default image,
specify the `defaultUri` configuration parameter.

```php
use Ork\Core\Image\Gravatar;

echo new Gravatar([
    'email' => 'foo@bar.com',
    'defaultUri' => 'http://foo.com/image.png',
]);
```
