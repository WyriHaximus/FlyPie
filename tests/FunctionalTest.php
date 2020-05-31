<?php

namespace WyriHaximus\Tests\FlyPie;

use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;
use WyriHaximus\FlyPie\FilesystemRegistry;

final class FunctionalTest extends TestCase
{
    public function setUp(): void
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
        $contents = FilesystemRegistry::retrieve('tests')->listContents('');
        $this->assertEqualsCanonicalizing([
            'bootstrap.php',
            'FilesystemsRegistryTest.php',
            'FilesystemsTraitTest.php',
            'FunctionalTest.php',
            'functions.php',
        ], array_map(function ($file) {
            return $file['path'];
        }, iterator_to_array($contents)));
    }
}
