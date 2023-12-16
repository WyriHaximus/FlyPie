<?php

namespace WyriHaximus\FlyPie\Factory;

use AsyncAws\S3\S3Client;
use League\Flysystem\AsyncAwsS3\AsyncAwsS3Adapter;
use League\Flysystem\FilesystemAdapter;

class AsyncAwsS3AdapterFactory
{
    /**
     * Return new Adapter
     *
     * @param array $config list of options parsed from dsn
     *
     * @return \League\Flysystem\FilesystemAdapter
     */
    public static function client(array $config): FilesystemAdapter
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
