<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\Zip as ZipAdapter;
use WyriHaximus\FlyPie\AdapterInterface;
use ZipArchive;

class Zip implements AdapterInterface
{
    /**
     * Create a Zip adapter.
     *
     * @param array $vars Array with options.
     *
     * @return ZipAdapter
     */
    public function create(array $vars)
    {
        $archive = null;
        if (isset($vars['archive']) && $vars['archive'] instanceof ZipArchive) {
            $archive = $vars['archive'];
        }
        $prefix = null;
        if (isset($vars['prefix'])) {
            $prefix = $vars['prefix'];
        }
        return new ZipAdapter($vars['path'], $archive, $prefix);
    }
}
