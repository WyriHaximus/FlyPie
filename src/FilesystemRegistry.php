<?php

namespace WyriHaximus\FlyPie;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;

class FilesystemRegistry
{
    const CONFIGURE_KEY_PREFIX = 'WyriHaximus.FlyPie.';
    
    protected static $_instances = [];

    public static function get($alias)
    {
        if (!isset(static::$_instances[$alias])) {
            static::$_instances[$alias] = new Filesystem(static::create($alias));
        }

        return static::$_instances[$alias];
    }

    protected static function create($alias)
    {
        if (!Configure::check(static::CONFIGURE_KEY_PREFIX . $alias)) {
            throw new \InvalidArgumentException('Filesystem "' . $alias . '" not configured');
        }

        if (Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.client') && Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.client') instanceof AdapterInterface) {
            return Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.client');
        }

        if (Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.factory')) {
            return static::factory(Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.factory'));
        }

        if (
            Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.vars') &&
            count(Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.vars')) > 0
        ) {
            return static::adapter(Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.adapter'), Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.vars'));
        }

        throw new \InvalidArgumentException('Filesystem "' . $alias . '" has no client or factory or paramaters specifiec to build a client');
    }

    protected static function factory($factory)
    {
        switch (gettype($factory)) {
            case 'callable':
                return $factory();
                break;
            case 'string':
                if (count(EventManager::instance()->listeners($factory)) > 0) {
                    return EventManager::instance()->dispatch($factory)->result;
                    break;
                }
            case 'array':
                if (function_exists($factory)) {
                    return call_user_func($factory);
                }
                break;
        }
    }

    protected static function adapter($adapter, $vars)
    {
        if (!class_exists($adapter)) {
            $adapter = '\\WyriHaximus\\FlyPie\\Adapter\\' . $adapter;
        }

        if (class_exists($adapter)) {
            return (new $adapter())->create($vars);
        }

        throw new \InvalidArgumentException('Unknown adapter');
    }
}
