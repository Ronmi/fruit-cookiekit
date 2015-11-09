<?php

namespace FruitTest\CookieKit;

use Fruit\CookieKit\PeclHttpCookieSetter;

class PeclHttpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension http
     */
    public function testGet()
    {
        $str = 'a=1; b=2';
        $expect = array('a' => '1', 'b' => '2');
        $setter = new PeclHttpCookieSetter($str);

        $this->assertEquals($expect, $setter->get());
    }
}
