# CookieKit

This package is part of Fruit Framework.

CookieKit is set of tools helping you manage cookies for you web app.

CookieKit is still under development, not usable now.

## Synopsis

Basically, just treat as normal array.

```php
$cookies = new Fruit\CookieKit\Cookie;
$myval = $cookies['mykey'];
unset($cookies['mykey']);
// do something, and then
$cookies['anotherkey'] = $anotherval;
$cookies->send(); // send Set-Cookies header
```

## Not only PHP built-in cookie support

Well-defined `CookieSetter` interface makes you easier to use CookieKit in almost any platform. For example, for your PHPSGI application, you don't even need to write your own `CookieSetter`:

```php
$headers = [];
$setter = new PeclHttpCookieSetter($environment['Cookies'], function($str) use ($headers) {
	$headers['Set-Cookies'] = $str;
});
$cookies = new Cookie($setter);
$cookies['mykey'] = $myval;
$cookies->send();
return [200, $headers, 'hello, world'];
```

Note: CookieKit might have proper support to PHPSGI when it comes to stable.

## License

Any version of MIT, GPL or LGPL.
