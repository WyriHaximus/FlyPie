FlyPie
======

Lightweight [Flysystem](https://github.com/thephpleague/flysystem) wrapper plugin for CakePHP v3.x

### Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `~`.

```
composer require wyrihaximus/fly-pie 
```

### Configuration ###

```php
    /**
     * FlySystem filesystems
     */
    'WyriHaximus' => [
        'FlyPie' => [
            'wow_screenshot_thumbnails' => [
                'adapter' => 'Local',
                'vars' => [
                    'path' => __DIR__,
                ],
            ],
        ],
    ],
```

### Usage ###

FlyPie comes with the `FilesystemsTrait` trait that lets you easily access filesystems. The only method in the trait, `filesystem($alias)`, provides access to the adapters you've defined in your configuration.

For example this lists all files on a `S3` bucket with thumbnails:
```php
$this->filesystem('s3_thumbnails')->listContents();
```

For more details on how to use [Flysystem, check out it's General Usage section](https://github.com/thephpleague/flysystem#general-usage).

### Supported Adapters ###

For all supported adapters see: [https://github.com/thephpleague/flysystem#adapters](https://github.com/thephpleague/flysystem#adapters)
