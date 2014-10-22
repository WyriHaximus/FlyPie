<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\Local as LocalAdapter;

class Local
{
    public function create($vars)
    {
        return new LocalAdapter($vars['path']);
    }
}
