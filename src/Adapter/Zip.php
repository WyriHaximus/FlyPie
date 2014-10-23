<?php

namespace WyriHaximus\FlyPie\Adapter;

use League\Flysystem\Adapter\Zip as ZipAdapter;
use ZipArchive;

class Zip
{
    public function create($vars)
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
