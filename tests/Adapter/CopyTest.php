<?php

namespace WyriHaximus\Tests\FlyPie\Adapter;

use WyriHaximus\FlyPie\Adapter\Copy;

class CopyTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf('League\Flysystem\Adapter\Copy', (new Copy())->create([
            'consumerKey' => '1',
            'consumerSecret' => '2',
            'accessToken' => '3',
            'tokenSecret' => '4',
            'prefix' => 'prefix',
        ]));
    }
}
