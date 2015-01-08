<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\Local as LocalAdapter;
use WyriHaximus\FlyPie\AdapterInterface;

class Local implements AdapterInterface
{
    /**
     * Create a Local adapter.
     *
     * @param array $vars Array with options.
     *
     * @return LocalAdapter
     */
    public function create(array $vars)
    {
        return new LocalAdapter($vars['path']);
    }
}
