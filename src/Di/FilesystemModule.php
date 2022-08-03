<?php

namespace WyriHaximus\FlyPie\Di;

use Ray\Di\AbstractModule;

class FilesystemModule extends AbstractModule
{
    public function configure()
    {
        $this
            ->bind('League\Flysystem\FilesystemInterface')
            ->annotatedWith('WyriHaximus\FlyPie\Di\FilesystemInject')
            ->toProvider('WyriHaximus\FlyPie\Di\FilesystemProvider');
    }
}
