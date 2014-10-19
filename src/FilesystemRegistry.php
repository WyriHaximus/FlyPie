<?php

namespace WyriHaximus\FlyPie;

use Cake\Core\Configure;

class FilesystemRegistry
{
    protected static $_instances = [];

    public static function get($alias)
    {
        if (!isset(static::$_instances[$alias])) {
            static::$_instances[$alias] = static::create($alias);
        }

        return static::$_instances[$alias];
    }

    protected static function create($alias)
    {
        if (!Configure::check('WyriHaximus.FlyPie.' . $alias)) {
            throw new \InvalidArgumentException('Filesystem "' . $alias . '" not configured');
        }

        $factory = Configure::read('WyriHaximus.FlyPie.' . $alias . '.client');
        return $factory();
    }
}
