<?php

namespace FruitTest\CookieKit;

use Fruit\CookieKit\CookieSetter;
use Fruit\CookieKit\CookieSpec;

class MockCookieSetter implements CookieSetter
{
    public $cookies;
    public $del;
    public $mod;

    public function __construct(array $cookies)
    {
        $this->del = array();
        $this->mod = array();
        $this->cookies = $cookies;
    }

    public function get()
    {
        return $this->cookies;
    }

    public function set(array $delete, array $cookies) {
        $this->del = array_fill_keys($delete, true);
        $this->mod = $cookies;
        return true;
    }
}
