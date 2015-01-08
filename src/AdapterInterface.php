<?php

namespace WyriHaximus\FlyPie;

interface AdapterInterface
{
    /**
     * Create a Flysystem adapter.
     *
     * @param array $vars Array with options.
     *
     * @return \League\Flysystem\AdapterInterface
     */
    public function create(array $vars);
}
