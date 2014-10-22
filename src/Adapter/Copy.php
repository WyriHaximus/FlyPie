<?php

namespace WyriHaximus\FlyPie\Adapter;

use Barracuda\Copy\API;
use League\Flysystem\Adapter\Copy as CopyAdapter;

class Copy
{
    public function create($vars)
    {
        $client = new API($vars['consumerKey'], $vars['consumerSecret'], $vars['accessToken'], $vars['tokenSecret']);
        $prefix = null;
        if (isset($vars['prefix'])) {
            $prefix = $vars['prefix'];
        }
        return new CopyAdapter($client, $prefix);
    }
}
