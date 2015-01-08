<?php

namespace WyriHaximus\FlyPie\Adapter;

use Barracuda\Copy\API;
use League\Flysystem\Adapter\Copy as CopyAdapter;
use WyriHaximus\FlyPie\AdapterInterface;

class Copy implements AdapterInterface
{
    /**
     * Create a Copy adapter.
     *
     * @param array $vars Array with options.
     *
     * @return CopyAdapter
     */
    public function create(array $vars)
    {
        $client = new API($vars['consumerKey'], $vars['consumerSecret'], $vars['accessToken'], $vars['tokenSecret']);
        $prefix = null;
        if (isset($vars['prefix'])) {
            $prefix = $vars['prefix'];
        }
        return new CopyAdapter($client, $prefix);
    }
}
