<?php

namespace Fruit\CookieKit;

class CookieSpec
{
    public $expire;
    public $path;
    public $domain;
    public $secure;
    public $httponly;

    public function __construct(
        $expire = 0,
        $path = '',
        $domain = '',
        $secure = false,
        $httponly = false
    ) {
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }

    public function expireAfter($seconds)
    {
        $this->expire = time() + $seconds;
        return $this;
    }
}
