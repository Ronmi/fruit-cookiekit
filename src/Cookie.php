<?php

namespace Fruit\CookieKit;

use ArrayObject;
use ArrayAccess;
use IteratorAggregate;

class Cookie implements ArrayAccess, IteratorAggregate
{
    private $origin; // cookies received from client.
    private $delete; // cookies to be deleted.
    private $modify; // cookies modified or appended. each cookie is an [value, http?]
    private $snapshot;
    private $setter;

    public function __construct(CookieSetter $setter = null)
    {
        if ($setter == null) {
            $setter = new DefaultCookieSetter;
        }
        $cookies = $setter->get();
        $this->origin = $cookies;
        $this->delete = array();
        $this->modify = array();
        $this->snapshot = $cookies;
        $this->setter = $setter;
    }

    /**
     * Set cookie, used only when you need to specify CookieSpec
     *
     * @param $spec CookieSpec object specifies attributes of cookie.
     */
    public function set($offset, $value, CookieSpec $spec = null)
    {
        $this->modify[$offset] = array($value, $spec);
        $this->snapshot[$offset] = $value;

        $this->delete[$offset] = false;
    }

    /**
     * Calling cookie setter to set/delete cookies
     */
    public function send()
    {
        $pendingDel = array_keys(array_filter($this->delete));
        if (!$this->setter->set($pendingDel, $this->modify)) {
            return false;
        }

        $this->delete = array();
        $this->modify = array();
        $this->origin = $this->snapshot;
        return true;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->snapshot);
    }

    public function offsetGet($offset)
    {
        return $this->snapshot[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->modify)) {
            unset($this->modify[$offset]);
        }

        if (array_key_exists($offset, $this->snapshot)) {
            unset($this->snapshot[$offset]);
        }

        if (array_key_exists($offset, $this->origin)) {
            $this->delete[$offset] = true;
        }
    }

    public function getIterator()
    {
        return (new ArrayObject($this->snapshot))->getIterator();
    }
}
