<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\ReplicateAdapter;

class Replicate
{
    public function create($vars)
    {
        return new ReplicateAdapter($vars['source'], $vars['destination']);
    }
}
