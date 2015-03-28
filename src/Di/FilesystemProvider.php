<?php

namespace WyriHaximus\FlyPie\Di;

use Ray\Di\ProviderInterface;
use WyriHaximus\FlyPie\FilesystemsTrait;

class FilesystemProvider implements ProviderInterface
{
    use FilesystemsTrait;
}
