<?php

namespace WyriHaximus\Tests\FlyPie;

use Cake\Core\Configure;
use WyriHaximus\FlyPie\FilesystemRegistry;

class FilesystemsRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveFail()
    {
        FilesystemRegistry::retrieve('nonexisting');
    }

    public function testRetrieve()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'adapter' => 'Local',
            'vars' => [
                'path' => __DIR__,
            ],
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }
}
