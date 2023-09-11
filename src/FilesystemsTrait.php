<?php

namespace WyriHaximus\FlyPie;

use League\Flysystem\Filesystem;

trait FilesystemsTrait
{
    /**
     * Proxy to the FilesystemRegistry.
     *
     * @param string $alias Alias name requested.
     *
     * @return \League\Flysystem\Filesystem
     */
    public function filesystem(string $alias): Filesystem
    {
        return FilesystemRegistry::retrieve($alias);
    }
}
