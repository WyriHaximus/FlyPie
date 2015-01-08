<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\ReplicateAdapter;
use WyriHaximus\FlyPie\AdapterInterface;

class Replicate implements AdapterInterface
{
    /**
     * Create a Replicate adapter.
     *
     * @param array $vars Array with options.
     *
     * @return ReplicateAdapter
     */
    public function create(array $vars)
    {
        return new ReplicateAdapter($vars['source'], $vars['destination']);
    }
}
