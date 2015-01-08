<?php

namespace WyriHaximus\FlyPie;

trait FilesystemsTrait
{
    /**
     * Proxy to the FilesystemRegistry.
     *
     * @param string $alias Alias name requested.
     *
     * @return mixed
     */
    public function filesystem($alias)
    {
        return FilesystemRegistry::get($alias);
    }
}
