<?php

namespace WyriHaximus\Tests\FlyPie;

use Cake\Core\Configure;
use Cake\Event\EventManager;
use WyriHaximus\FlyPie\FilesystemRegistry;

class FilesystemsRegistryTest extends \PHPUnit_Framework_TestCase
{
    protected $callbackFired = false;
    protected static $callbackFiredStatic = false;

    public function setUp()
    {
        parent::setUp();
        FilesystemRegistry::reset();
        $this->callbackFired = false;
        $GLOBALS['THIS_callbackFiredStatic'] = false;
        $GLOBALS['THIS'] = $this;
    }

    public function tearDown()
    {
        unset($GLOBALS['THIS'], $GLOBALS['THIS_callbackFiredStatic']);
    }

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

    public function testRetrieveClient()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'client' => $this->getMock('League\Flysystem\AdapterInterface'),
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }

    public function testRetrieveFactoryCallback()
    {
        $callbackFired = false;
        $callback = function () use (&$callbackFired) {
            $callbackFired = true;
            return $this->getMock('League\Flysystem\AdapterInterface');
        };
        Configure::write('WyriHaximus.FlyPie.existing', [
            'factory' => $callback,
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
        $this->assertTrue($callbackFired);
    }

    public function testRetrieveFactoryStringArray()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'factory' => [$this, 'testRetrieveFactoryStringArray_checker'],
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
        $this->assertTrue($this->callbackFired);
    }

    public function testRetrieveFactoryStringArray_checker()
    {
        $this->callbackFired = true;
        return $this->getMock('League\Flysystem\AdapterInterface');
    }

    public function testRetrieveFactoryStringStatic()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'factory' => 'WyriHaximus\Tests\FlyPie\testRetrieveFactoryStringStatic_checker',
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
        $this->assertTrue($GLOBALS['THIS_callbackFiredStatic']);
    }

    public function testRetrieveFactoryEvent()
    {
        $callbackFired = false;
        $callback = function () use (&$callbackFired) {
            $callbackFired = true;
            return $this->getMock('League\Flysystem\AdapterInterface');
        };
        EventManager::instance()->attach($callback, 'WyriHaximus.Tests.FlyPie.existing');
        Configure::write('WyriHaximus.FlyPie.existing', [
            'factory' => 'WyriHaximus.Tests.FlyPie.existing',
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
        $this->assertTrue($callbackFired);
        EventManager::instance()->detach($callback, 'WyriHaximus.Tests.FlyPie.existing');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveFactoryFail()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'factory' => 'liuovtwhvo5n8htwo34hnotuw34hot3p98wp9w5h9th9343',
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }

    public function testRetrieveAdapter()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'adapter' => 'Local',
            'vars' => [
                __DIR__,
            ],
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }

    public function testRetrieveAdapterFQCN()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'adapter' => '\\League\\Flysystem\\Adapter\\Local',
            'vars' => [
                __DIR__,
            ],
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveAdapterFail()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
            'adapter' => 'liuovtwhvo5n8htwo34hnotuw34hot3p98wp9w5h9th9343',
            'vars' => [
                __DIR__,
            ],
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveFailNothingDefined()
    {
        Configure::write('WyriHaximus.FlyPie.existing', [
        ]);
        $this->assertInstanceOf('League\Flysystem\Filesystem', FilesystemRegistry::retrieve('existing'));
    }
}
