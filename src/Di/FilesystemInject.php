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
    public string $optional = '';

    public function isOptional(): bool
    {
        return (bool)$this->optional;
    }

    public function getAlias(): string
    {
        return $this->optional;
    }
}
