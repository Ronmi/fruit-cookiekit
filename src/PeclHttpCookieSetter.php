<?php

namespace Fruit\CookieKit;

use http\Cookie as extCookie;

class PeclHttpCookieSetter implements CookieSetter
{
    private $callback;
    private $cookies;

    public function __construct($cookieString, callback $callback = null)
    {
        if ($callback === null) {
            $callback = function ($str) {
                header('Set-Cookie: ' . $str);
            };
        }
        $this->callback = $callback;

        $tmp = new extCookie($cookieString);
        $this->cookies = $tmp->getCookies();
    }

    public function get()
    {
        return $this->cookies;
    }

    public function set(array $delete, array $cookies)
    {
        $pending = array();
        $f = function ($spec, $key, $val) use ($pending) {
            if (!array_key_exists($spec, $pending)) {
                $pending[$spec] = array();
            }
            $pending[$spec][$key] = $val;
        };
        foreach ($delete as $key) {
            f(null, $key, '');
        }
        foreach ($cookies as $key => $data) {
            list ($val, $spec) = $data;
            $f($spec, $key, $val);
        }

        $callback = $this->callback;
        foreach ($pending as $spec => $data) {
            if ($spec === null) {
                $spec = new CookieSpec;
            }
            $flags = ($spec->secure?extCookie::SECURE:0);
            $flags += ($spec->httponly?extCookie::HTTPONLY:0);
            $httpCookie = new extCookie(null, $flags);
            $httpCookie->setCookies($data);
            $httpCookie->setExpires($spec->expire);
            $httpCookie->setPath($spec->path);
            $httpCookie->setDomain($spec->domain);
            $callback($httpCookie->toString());
        }
        return true;
    }
}
