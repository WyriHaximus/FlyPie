<?php

namespace WyriHaximus\Tests\FlyPie\Adapter;

use WyriHaximus\FlyPie\Adapter\Zip;

class ZipTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf('League\Flysystem\Adapter\Zip', (new Zip())->create([
            'path' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'wyrihaximus_fly_pie_test_adapter_' . uniqid() . '.zip',
            'prefix' => 'prefix',
            'archive' => new \ZipArchive(),
        ]));
    }
}
