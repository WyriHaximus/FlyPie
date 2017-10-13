FlyPie
======

Lightweight [Flysystem](http://flysystem.thephpleague.com/) wrapper/configuration plugin for CakePHP v3.x

## Installation ##

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `~`.

```bash
composer require wyrihaximus/fly-pie 
```

## Bootstrap ##

Add the following to your `config/bootstrap.php` to load the plugin.

```php
Plugin::load('WyriHaximus/FlyPie', ['bootstrap' => true]);
```

## Configuration ##

Example configuration (for in `config/app.php`):

```php
/**
 * FlySystem filesystems
 */
'WyriHaximus' => [
    'FlyPie' => [
        's3_thumbnails' => [
            'adapter' => 'Local',
            'vars' => [
                'path' => __DIR__,
            ],
        ],
    ],
],
```

At first it's namespaced within `WyriHaximus.FlyPie` to make sure it doesn't interfere with any other plugins. Secondly it's an associative array where every key is the alias for a specific plugin configuration. That is `s3_thumbnails` in our example. So when you need that filesystem somewhere in your project you can call `$this->filesystem('s3_thumbnails');` on the trait. 

### Configuration keys ###

* `adapter` - adapter name or full qualified class name to use for this filesystem
* `vars` - associative array containing the required settings for FlyPie to build an adapter for you. (This is required in case you don't use a factory or provide the client.)
* `client` - a prebuild client
* `factory` - a callback, array (class instance, methodname), string (static class method or function name or event name) that can be utilized as a factory to build the adapter

Check out [config/config.sample.php](config/config.sample.php) for more details.

## Usage ##

FlyPie comes with the `FilesystemsTrait` trait that lets you easily access filesystems. The only method in the trait, `filesystem($alias)`, provides access to the adapters you've defined in your configuration.

For example this lists all files in a `S3` bucket with thumbnails:
```php
$this->filesystem('s3_thumbnails')->listContents();
```

For more details on how to use [Flysystem, check out its General Usage section](https://github.com/thephpleague/flysystem#general-usage).

## Supported Adapters ##

For all supported adapters see: [https://github.com/thephpleague/flysystem#adapters](https://github.com/thephpleague/flysystem#adapters)

## License ##

Copyright 2017 [Cees-Jan Kiewiet](http://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

