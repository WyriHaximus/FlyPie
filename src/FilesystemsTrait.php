<?php

namespace WyriHaximus\FlyPie;

use League\Flysystem\FilesystemInterface;

trait FilesystemsTrait
{
    /**
     * Proxy to the FilesystemRegistry.
     *
     * @param string $alias Alias name requested.
     *
     * @return FilesystemInterface
     */
    public function filesystem($alias)
    {
        return FilesystemRegistry::retrieve($alias);
    }
}
