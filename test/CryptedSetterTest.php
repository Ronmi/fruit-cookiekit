<?php

namespace FruitTest\CookieKit;

use Fruit\CookieKit\Cookie;
use Fruit\CryptoKit\MockCrypter;
use Fruit\CookieKit\CryptedSetter;

class CryptedSetterTest extends \PHPUnit_Framework_TestCase
{
    public function testCrypt()
    {
        $cs = new MockCookieSetter(array());
        $crypt = new MockCrypter;
        $setter = new CryptedSetter($crypt, $cs);
        $cookie = new Cookie($setter);
        $cookie['test'] = 'test ';
        $this->assertTrue($cookie->send());

        $data = $cs->mod;
        array_walk($data, function(&$val){
            $val = $val[0];
        });
        $setter = new CryptedSetter($crypt, new MockCookieSetter($data));
        $cookie = new Cookie($setter);
        $this->assertEquals('test ', $cookie['test']);
    }
}
