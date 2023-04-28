# \Ork\Core\Str\Base32

BASE32 encoding can be useful in cases where one might normally use BASE64 but
a case-insensitive result is needed.

* [Encoding](#encoding)
* [Decoding](#decoding)
* [Specifying a custom alphabet](#specifying-a-custom-alphabet)

## Encoding

```php
use Ork\Core\Str\Base32;

$base32 = new Base32();
$encoded = $base32->encode($plaintext);
```

## Decoding

```php
use Ork\Core\Str\Base32;

$base32 = new Base32();
$plaintext = $base32->decode($encoded);
```

## Specifying a custom alphabet

By default, the alphabet defined in
[RFC4648](https://datatracker.ietf.org/doc/html/rfc4648#section-6) is used. An
alternative alphabet may be provided via the `alphabet` parameter.

```php
$base32 = new Base32(['alphabet' => '0123456789abcdefghijklmnopqrstuv']);
$encoded = $base32->encode($plaintext);
```
