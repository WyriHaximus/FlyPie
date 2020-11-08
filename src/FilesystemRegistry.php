<?php
declare(strict_types=1);

namespace WyriHaximus\FlyPie;

use Cake\Core\Configure;
use Cake\Core\StaticConfigTrait;
use Cake\Event\EventManager;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;

class FilesystemRegistry
{
    use StaticConfigTrait;

    // @codingStandardsIgnoreStart
    public const CONFIGURE_KEY_PREFIX = 'WyriHaximus.FlyPie.';
    public const INVALID_ARGUMENT_MSG = 'Filesystem "%s" has no client or factory or parameters specific to build a client';

    /**
     * An array mapping url schemes to fully qualified driver class names
     *
     * @var array
     */
    protected static $_dsnClassMap = [
        's3' => 'WyriHaximus\FlyPie\Factory\AsyncAwsS3AdapterFactory',
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Adapter class cache.
     *
     * @var array
     */
    public static $adapterClasses = [];

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
     * @return \League\Flysystem\Filesystem
     */
    public static function retrieve($alias)
    {
        if (!isset(static::$instances[$alias])) {
            $adapter = static::create($alias);
            static::$adapterClasses[$alias] = get_class($adapter);
            static::$instances[$alias] = new Filesystem($adapter);
        }

        return static::$instances[$alias];
    }

    /**
     * Reset the instance array back to nothing.
     *
     * @return void
     */
    public static function reset()
    {
        static::$instances = [];
        static::$adapterClasses = [];
    }

    /**
     * Gather the information to build an adapter.
     *
     * @param string $alias The alias chosen for the adapter we want.
     *
     * @throws \InvalidArgumentException Thrown when no matching configuration is found.
     *
     * @return \League\Flysystem\FilesystemAdapter
     */
    protected static function create($alias)
    {
        $aliasConfigKey = static::CONFIGURE_KEY_PREFIX . $alias;
        if (!Configure::check($aliasConfigKey)) {
            throw new \InvalidArgumentException('Filesystem "' . $alias . '" not configured');
        }

        if (static::existsAndInstanceOf($aliasConfigKey)) {
            return Configure::read($aliasConfigKey . '.client');
        }

        if (Configure::check($aliasConfigKey . '.factory')) {
            return static::factory(Configure::read($aliasConfigKey . '.factory'));
        }

        if (self::existsAndVarsCount($aliasConfigKey)) {
            return static::adapter(static::read($aliasConfigKey, 'adapter'), static::read($aliasConfigKey, 'vars'));
        }

        if (self::existsWithDsn($aliasConfigKey)) {
            return static::adapterFromDsn(Configure::read($aliasConfigKey . '.url'));
        }

        throw new \InvalidArgumentException(sprintf(static::INVALID_ARGUMENT_MSG, $alias));
    }

    /**
     * Check if $aliasConfigKey exists and has the correct instance.
     *
     * @param string $aliasConfigKey Key to check for.
     *
     * @return bool
     */
    protected static function existsAndInstanceOf($aliasConfigKey)
    {
        return Configure::check($aliasConfigKey . '.client') &&
            Configure::read($aliasConfigKey . '.client') instanceof FilesystemAdapter;
    }

    /**
     * Check if $aliasConfigKey exists and if it has vars defined.
     *
     * @param string $aliasConfigKey Key to check for.
     *
     * @return bool
     */
    protected static function existsAndVarsCount($aliasConfigKey)
    {
        return Configure::check($aliasConfigKey . '.vars') &&
            is_array(Configure::read($aliasConfigKey . '.vars'));
    }

    /**
     * Check if $aliasConfigKey exists and if it has url defined.
     *
     * @param string $aliasConfigKey Key to check for.
     *
     * @return bool
     */
    protected static function existsWithDsn($aliasConfigKey)
    {
        return Configure::check($aliasConfigKey . '.url') &&
            !empty(Configure::read($aliasConfigKey . '.url'));
    }

    /**
     * Read from Configure for given $aliasConfigKey . '.' . $field.
     *
     * @param string $aliasConfigKey Config alias key.
     * @param string $field          Config field.
     *
     * @return mixed
     */
    protected static function read($aliasConfigKey, $field)
    {
        return Configure::read($aliasConfigKey . '.' . $field);
    }

    /**
     * Adapter factory.
     *
     * @param callable|string|array $factory The factory to use and build the adapter.
     *
     * @throws \InvalidArgumentException Thrown when no suitable factory is found.
     *
     * @return mixed
     */
    protected static function factory($factory)
    {
        if (is_callable($factory)) {
            return $factory();
        }

        $type = gettype($factory);
        if ($type == 'string' && count(EventManager::instance()->listeners($factory)) > 0) {
            return EventManager::instance()->dispatch($factory)->getResult();
        }

        throw new \InvalidArgumentException('No suitable factory found');
    }

    /**
     * Instantiate adapter.
     *
     * @param string $adapter Adapter to create.
     * @param array  $vars    Vars to pass to adapter.
     *
     * @throws \InvalidArgumentException Thrown when the given adapter class doesn't exists.
     *
     * @return \League\Flysystem\FilesystemAdapter
     */
    protected static function adapter($adapter, array $vars)
    {
        if (class_exists($adapter)) {
            return (new \ReflectionClass($adapter))->newInstanceArgs($vars);
        }

        // AsyncAwsS3, AwsS3V3, Ftp and ZipArchive
        $leagueAdapter = '\\League\\Flysystem\\' . $adapter . '\\' . $adapter . 'Adapter';
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($vars);
        }

        // Local and InMemory
        $leagueAdapter = '\\League\\Flysystem\\' . $adapter . '\\' . $adapter . 'FilesystemAdapter';
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($vars);
        }

        // Sftp
        $leagueAdapter = '\\League\\Flysystem\\PhpseclibV2\\' . $adapter . 'Adapter';
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($vars);
        }

        throw new \InvalidArgumentException('Unknown adapter');
    }

    /**
     * Parse dsn, map configuration and instantiate adapter.
     *
     * @param string $dsn Data Source Name to parse and map.
     *
     * @throws \InvalidArgumentException Thrown when the given adapter class doesn't exists.
     *
     * @return \League\Flysystem\FilesystemAdapter
     */
    protected static function adapterFromDsn(string $dsn)
    {
        $vars = static::parseDsn($dsn);

        if (!class_exists($vars['className'])) {
            throw new \InvalidArgumentException('Unknown adapter');
        }

        return $vars['className']::client($vars);
    }
}
