<?php

namespace WyriHaximus\Tests\FlyPie\Adapter;

use WyriHaximus\FlyPie\Adapter\Local;

class LocalTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf('League\Flysystem\Adapter\Local', (new Local())->create([
            'path' => __DIR__,
        ]));
    }
}
