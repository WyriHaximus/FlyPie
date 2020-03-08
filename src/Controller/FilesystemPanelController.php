<?php

namespace WyriHaximus\FlyPie\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use WyriHaximus\FlyPie\FilesystemsTrait;

/**
 * Class FilesystemPanelController
 * @package WyriHaximus\FlyPie\Controller
 */
class FilesystemPanelController extends Controller
{
    use FilesystemsTrait;

    /**
     * Before filter handler.
     *
     * @param \Cake\Event\EventInterface $event The event.
     *
     * @return void
     *
     * @throws \Cake\Http\Exception\NotFoundException Throw no found when debug is disabled.
     */
    public function beforeFilter(EventInterface $event)
    {
        // TODO add config override.
        if (!Configure::read('debug')) {
            throw new NotFoundException();
        }
    }

    /**
     * List contents from directory $path on filesystem $filesystem.
     *
     * @param string $filesystem Filesystem to list contents in $path from.
     * @param string $path       List contents from here.
     *
     * @return void
     */
    public function listContents($filesystem, $path = '')
    {
        $this->set([
            '_serialize' => [
                'directoryContents',
            ],
            'directoryContents' => $this->filesystem($filesystem)->listContents($path),
        ]);
    }
}
