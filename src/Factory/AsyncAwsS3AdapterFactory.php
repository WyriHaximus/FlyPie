<?php

namespace WyriHaximus\FlyPie\Factory;

use AsyncAws\S3\S3Client;
use League\Flysystem\AsyncAwsS3\AsyncAwsS3Adapter;

class AsyncAwsS3AdapterFactory
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
            'accessKeyId' => $config['username'],
            'accessKeySecret' => $config['password'],
            'region' => $config['region'],
        ]);

        return new AsyncAwsS3Adapter($client, $config['host'], $config['path']);
    }
}
