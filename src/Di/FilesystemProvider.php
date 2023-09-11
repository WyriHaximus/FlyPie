<?php

namespace WyriHaximus\FlyPie\Di;

use League\Flysystem\Filesystem;
use Ray\Di\InjectionPointInterface;
use Ray\Di\ProviderInterface;
use WyriHaximus\FlyPie\FilesystemsTrait;

class FilesystemProvider implements ProviderInterface
{
    use FilesystemsTrait;

    /**
     * @var \Ray\Di\InjectionPointInterface
     */
    private InjectionPointInterface $ip;

    public function __construct(InjectionPointInterface $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return \League\Flysystem\Filesystem
     * @throws \Exception
     */
    public function get(): Filesystem
    {
        foreach ($this->ip->getQualifiers() as $qualifier) {
            if ($qualifier instanceof FilesystemInject) {
                return $this->filesystem($qualifier->getAlias());
            }
        }

        throw new \Exception('Missing annotation');
    }
}
