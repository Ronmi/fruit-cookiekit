<?php

namespace Fruit\CookieKit;

class DefaultCookieSetter implements CookieSetter
{
    public static function __set_state(array $props)
    {
        return new self;
    }

    public function get()
    {
        return $_COOKIE;
    }

    public function set(array $delete, array $cookies)
    {
        foreach ($delete as $key) {
            if (!setcookie($key, '', time() - 3600)) {
                return false;
            }
        }
        foreach ($cookies as $key => $data) {
            list ($val, $spec) = $data;
            $expire = 0;
            $path = '';
            $domain = '';
            $secure = false;
            $httponly = false;
            if ($spec !== null) {
                $expire = $spec->expire;
                $path = $spec->path;
                $domain = $spec->domain;
                $secure = $spec->secure;
                $httponly = $spec->httponly;
            }
            if (!setcookie($key, $val, $expire, $path, $domain, $secure, $httponly)) {
                return false;
            }
        }
        return true;
    }
}
