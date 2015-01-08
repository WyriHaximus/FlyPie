<?php

namespace WyriHaximus\Tests\FlyPie\Adapter;

use WyriHaximus\FlyPie\Adapter\Replicate;

class ReplicateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf('League\Flysystem\Adapter\ReplicateAdapter', (new Replicate())->create([
            'source' => $this->getMock('League\Flysystem\AdapterInterface'),
            'destination' => $this->getMock('League\Flysystem\AdapterInterface'),
        ]));
    }
}
