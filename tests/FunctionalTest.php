<?php

namespace WyriHaximus\Tests\FlyPie;

use Cake\Core\Configure;
use WyriHaximus\FlyPie\FilesystemRegistry;

final class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        FilesystemRegistry::reset();
    }

    public function testRetrieve()
    {
        Configure::write('WyriHaximus.FlyPie.tests', [
            'adapter' => 'Local',
            'vars' => [
                __DIR__,
            ],
        ]);
        $contents = FilesystemRegistry::retrieve('tests')->listContents();
        $this->assertSame([
            'bootstrap.php',
            'FilesystemsRegistryTest.php',
            'FilesystemsTraitTest.php',
            'FunctionalTest.php',
            'functions.php',
        ], array_map(function ($file) {
            return $file['path'];
        }, $contents));
    }
}
