<?php

namespace WyriHaximus\FlyPie\Di;

use Ray\Di\InjectionPointInterface;
use Ray\Di\ProviderInterface;
use WyriHaximus\FlyPie\FilesystemsTrait;

class FilesystemProvider implements ProviderInterface
{
    use FilesystemsTrait;

    /**
     * @var InjectionPointInterface
     */
    private $ip;

    public function __construct(InjectionPointInterface $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return \League\Flysystem\AdapterInterface
     * @throws \Exception
     */
    public function get()
    {
        foreach ($this->ip->getQualifiers() as $qualifier) {
            if ($qualifier instanceof FilesystemInject) {
                return $this->filesystem($qualifier->getAlias());
            }
        }

        throw new \Exception('Missing annotation');
    }
}
