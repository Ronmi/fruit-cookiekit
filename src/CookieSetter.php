<?php

namespace Fruit\CookieKit;

interface CookieSetter
{
    /**
     * Get original cookies. See DefaultCookieSetter::get for detail.
     */
    public function get();

    /**
     * Really send cookies.
     *
     * @param $delete array of keys to delete.
     * @param $cookies array of [cookie_key => [cookie_val, CookieSpec | null]]
     */
    public function set(array $delete, array $cookies);
}
