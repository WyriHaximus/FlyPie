<?php

namespace WyriHaximus\FlyPie\Di;

use Cake\Core\Configure;
use Ray\Di\AbstractModule;

class FilesystemModule extends AbstractModule
{
    public function configure()
    {
        foreach (Configure::read('WyriHaximus.FlyPie') as $alias => $adapter) {
            $this->
                bind('League\Flysystem\FilesystemInterface')->
                annotatedWith($alias)->
                toProvider('WyriHaximus\FlyPie\Di\FilesystemProvider');
        }
    }
}
