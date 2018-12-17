<?php

namespace WyriHaximus\FlyPie;

use Cake\Core\Configure;
use Cake\Core\StaticConfigTrait;
use Cake\Event\EventManager;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Plugin\EmptyDir;
use League\Flysystem\Plugin\GetWithMetadata;
use League\Flysystem\Plugin\ListFiles;
use League\Flysystem\Plugin\ListPaths;
use League\Flysystem\Plugin\ListWith;

class FilesystemRegistry
{
    use StaticConfigTrait;

    const CONFIGURE_KEY_PREFIX = 'WyriHaximus.FlyPie.';
    const INVALID_ARGUMENT_MSG = 'Filesystem "%s" has no client or factory or parameters specific to build a client';

    /**
     * An array mapping url schemes to fully qualified driver class names
     *
     * @return array
     */
    protected static $_dsnClassMap = [
        's3' => 'League\Flysystem\AwsS3v3\AwsS3Adapter',
    ];

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
     * @return FilesystemInterface
     */
    public static function retrieve($alias)
    {
        if (!isset(static::$instances[$alias])) {
            static::$instances[$alias] = static::addPlugins(new Filesystem(static::create($alias)));
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

        if(self::existsWithDsn($aliasConfigKey)) {
            return static::adapterFromDsn(Configure::read($aliasConfigKey. '.url'));
        }

        throw new \InvalidArgumentException(sprintf(static::INVALID_ARGUMENT_MSG, $alias));
    }

    /**
     * Check if $aliasConfigKey exists and has the correct instance.
     *
     * @param string $aliasConfigKey Key to check for.
     *
     * @return boolean
     */
    protected static function existsAndInstanceOf($aliasConfigKey)
    {
        return Configure::check($aliasConfigKey . '.client') &&
            Configure::read($aliasConfigKey . '.client') instanceof AdapterInterface;
    }

    /**
     * Check if $aliasConfigKey exists and if it has vars defined.
     *
     * @param string $aliasConfigKey Key to check for.
     *
     * @return boolean
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
     * @return boolean
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
            return EventManager::instance()->dispatch($factory)->result;
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
     * @return AdapterInterface
     */
    protected static function adapter($adapter, array $vars)
    {
        if (class_exists($adapter)) {
            return (new \ReflectionClass($adapter))->newInstanceArgs($vars);
        }

        $leagueAdapter = '\\League\\Flysystem\\Adapter\\' . $adapter;
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($vars);
        }

        $leagueAdapter = '\\League\\Flysystem\\' . $adapter . '\\' . $adapter . 'Adapter';
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
     * @return AdapterInterface
     */
    protected static function adapterFromDsn(string $dsn){
        $vars = static::parseDsn($dsn);

        if(class_exists($vars['className'])){
            throw new \InvalidArgumentException('Unknown adapter');
        }

        if($vars['className'] == 'League\Flysystem\AwsS3v3\AwsS3v3Adapter'){
            $client = (new \ReflectionClass('Aws\S3\S3Client'))->newInstanceArgs([
                'credentials' => [
                    'key' => $vars['username'],
                    'secret' => $vars['password'],
                ],
                'region' => $vars['region'],
                'version' => $vars['version'],
            ]);

            return static::adapter($vars['className'], [$client, $vars['host'], $vars['path']]);
        }
    }

    /**
     * Add default plugins to filesystem
     *
     * @param FilesystemInterface $filesystem The filesystem.
     *
     * @return FilesystemInterface
     */
    protected static function addPlugins(FilesystemInterface $filesystem)
    {
        foreach ([
            new EmptyDir(),
            new GetWithMetadata(),
            new ListFiles(),
            new ListPaths(),
            new ListWith(),
        ] as $plugin) {
            $plugin->setFilesystem($filesystem);
            $filesystem->addPlugin($plugin);
        }
        return $filesystem;
    }
}
