<?php

namespace WyriHaximus\FlyPie\Panel;

use Cake\Core\Configure;
use DebugKit\DebugPanel;

class FilesystemPanel extends DebugPanel
{
    const CONFIGURE_KEY = 'WyriHaximus.FlyPie';

    // @codingStandardsIgnoreStart
    public $plugin = 'WyriHaximus/FlyPie';
    // @codingStandardsIgnoreEnd

    /**
     * Return all data with: All configured filesystems.
     *
     * @return array
     */
    public function data()
    {
        return [
            'filesystems' => array_keys((array)Configure::read(self::CONFIGURE_KEY)),
        ];
    }
}
