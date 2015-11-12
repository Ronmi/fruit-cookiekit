<?php

namespace Fruit\CookieKit;

use Fruit\CryptoKit\Crypter;

class CryptedSetter implements CookieSetter
{
    private $setter;
    private $crypter;

    public function __construct(Crypter $crypter, CookieSetter $setter)
    {
        $this->setter = $setter;
        $this->crypter = $crypter;
    }

    public function get()
    {
        $data = $this->setter->get();
        array_walk($data, function (&$val) {
            $val = $this->crypter->decrypt($val);
        });
        return $data;
    }

    public function set(array $delete, array $cookies)
    {
        array_walk($cookies, function(&$val){
            $val[0] = $this->crypter->encrypt($val[0]);
        });
        return $this->setter->set($delete, $cookies);
    }
}
