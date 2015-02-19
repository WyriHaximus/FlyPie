<?php

namespace WyriHaximus\FlyPie;

use League\Flysystem\AdapterInterface;

trait FilesystemsTrait
{
    /**
     * Proxy to the FilesystemRegistry.
     *
     * @param string $alias Alias name requested.
     *
     * @return AdapterInterface
     */
    public function filesystem($alias)
    {
        return FilesystemRegistry::retrieve($alias);
    }
}
