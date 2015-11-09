<?php

namespace FruitTest\CookieKit;

use Fruit\CookieKit\Cookie;
use Fruit\CookieKit\CookieSpec;
use Fruit\CookieKit\CookieSetter;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    private function setter(array $cookies)
    {
        return new MockCookieSetter($cookies);
    }

    public function testAdd()
    {
        $setter = $this->setter(array());
        $cookie = new Cookie($setter);
        $cookie['test'] = 'test';
        $spec = new CookieSpec(0, '', '', false, true);
        $cookie->set('test2', 'test2', $spec);
        $this->assertTrue($cookie->send());

        $this->assertEquals(0, count($setter->del));
        $this->assertEquals(2, count($setter->mod));
        $this->assertEquals(array('test', null), $setter->mod['test']);
        $this->assertEquals(array('test2', $spec), $setter->mod['test2']);
    }

    public function testModify()
    {
        $setter = $this->setter(array(
            'test' => 'a',
            'test2' => 'b',
        ));
        $cookie = new Cookie($setter);
        $cookie['test'] = 'test';
        $spec = new CookieSpec(0, '', '', false, true);
        $cookie->set('test2', 'test2', $spec);
        $this->assertTrue($cookie->send());

        $this->assertEquals(0, count($setter->del));
        $this->assertEquals(2, count($setter->mod));
        $this->assertEquals(array('test', null), $setter->mod['test']);
        $this->assertEquals(array('test2', $spec), $setter->mod['test2']);
    }

    public function testDelete()
    {
        $setter = $this->setter(array('test' => 'a'));
        $cookie = new Cookie($setter);
        $cookie['test2'] = 'b';

        unset($cookie['test']);
        unset($cookie['test2']);
        $this->assertTrue($cookie->send());

        // only delete from origin will cause a setter action
        $this->assertEquals(1, count($setter->del));
        $this->assertArrayHasKey('test', $setter->del);
        // delete from appended data will only delete it from pending queue
        $this->assertEquals(0, count($setter->mod));
        $this->assertArrayNotHasKey('test', $cookie);
        $this->assertArrayNotHasKey('test2', $cookie);
    }
}
