<?php

namespace WyriHaximus\FlyPie;

trait FilesystemsTrait
{
    public function filesystem($alias)
    {
        return FilesystemRegistry::get($alias);
    }
}
