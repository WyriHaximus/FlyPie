<?php

namespace WyriHaximus\FlyPie;

trait FilesystemsTrait
{
    /**
     * Proxy to the FilesystemRegistry.
     *
     * @param string $alias Alias name requested.
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    public function filesystem($alias)
    {
        return FilesystemRegistry::retrieve($alias);
    }
}
