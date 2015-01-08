<?php

namespace WyriHaximus\FlyPie;

use Cake\Core\Configure;
use Cake\Event\EventManager;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;

class FilesystemRegistry
{
    const CONFIGURE_KEY_PREFIX = 'WyriHaximus.FlyPie.';

    /**
     * Instance cache.
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Build and return a preconfigured adapter.
     *
     * @param string $alias The alias chosen for the adapter we want.
     *
     * @return AdapterInterface
     */
    public static function get($alias)
    {
        if (!isset(static::$instances[$alias])) {
            static::$instances[$alias] = new Filesystem(static::create($alias));
        }

        return static::$instances[$alias];
    }

    /**
     * Gather the information to build an adapter.
     *
     * @param string $alias The alias chosen for the adapter we want.
     *
     * @throws \InvalidArgumentException Thrown when no matching configuration is found.
     *
     * @return AdapterInterface
     */
    protected static function create($alias)
    {
        if (!Configure::check(static::CONFIGURE_KEY_PREFIX . $alias)) {
            throw new \InvalidArgumentException('Filesystem "' . $alias . '" not configured');
        }

        if (
            Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.client') &&
            Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.client') instanceof AdapterInterface
        ) {
            return Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.client');
        }

        if (Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.factory')) {
            return static::factory(Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.factory'));
        }

        if (
            Configure::check(static::CONFIGURE_KEY_PREFIX . $alias . '.vars') &&
            count(Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.vars')) > 0
        ) {
            return static::adapter(
                Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.adapter'),
                Configure::read(static::CONFIGURE_KEY_PREFIX . $alias . '.vars')
            );
        }

        throw new \InvalidArgumentException(
            'Filesystem "' . $alias . '" has no client or factory or paramaters specifiec to build a client'
        );
    }

    /**
     * Adapter factory.
     *
     * @param callable|string|array $factory The factory to use and build the adapter.
     *
     * @return mixed
     */
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
                // Falling through in case it's possible a function
            case 'array':
                if (function_exists($factory)) {
                    return call_user_func($factory);
                }
                break;
        }
    }

    /**
     * Instantiate adapter.
     *
     * @param string $adapter Adapter to create.
     * @param array  $vars    Vars to pass to adapter.
     *
     * @throw \InvalidArgumentException Thrown when the given adapter class doesn't exists.
     *
     * @return AdapterInterface
     */
    protected static function adapter($adapter, array $vars)
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
