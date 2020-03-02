<?php

namespace WyriHaximus\FlyPie\Di;

use Ray\Di\Di\InjectInterface;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
class FilesystemInject implements InjectInterface
{
    public $optional = '';

    public function isOptional()
    {
        return (bool)$this->optional;
    }

    public function getAlias()
    {
        return $this->optional;
    }
}
