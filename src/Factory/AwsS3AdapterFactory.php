<?php
namespace WyriHaximus\FlyPie\Factory;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class AwsS3AdapterFactory
{
    /**
     * Return new Adapter
     *
     * @param array $config list of options parsed from dsn
     *
     * @return \League\Flysystem\AdapterInterface|bool
     */
    public static function client($config)
    {
        $client = new S3Client([
            'credentials' => [
                'key' => $config['username'],
                'secret' => $config['password'],
            ],
            'region' => $config['region'],
            'version' => $config['version'],
        ]);

        return new AwsS3Adapter($client, $config['host'], $config['path']);
    }
}
