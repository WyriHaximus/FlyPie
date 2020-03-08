<?php

namespace WyriHaximus\FlyPie\Factory;

use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\S3Client;

class AwsS3AdapterFactory
{
    /**
     * Return new Adapter
     *
     * @param array $config list of options parsed from dsn
     *
     * @return \League\Flysystem\FilesystemAdapter|bool
     */
    public static function client($config)
    {
        $defaults = [
            'path' => '',
        ];
        $config += $defaults;
        $client = new S3Client([
            'credentials' => [
                'key' => $config['username'],
                'secret' => $config['password'],
            ],
            'region' => $config['region'],
            'version' => $config['version'],
        ]);

        return new S3FilesystemV2($client, $config['host'], $config['path']);
    }
}
