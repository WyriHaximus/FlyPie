<?php

namespace WyriHaximus\FlyPie\Panel;

use Cake\Core\Configure;
use Cake\Event\Event;
use DebugKit\DebugPanel;

class FilesystemPanel extends DebugPanel
{
    const CONFIGURE_KEY = 'WyriHaximus.FlyPie';

    public $plugin = 'WyriHaximus/FlyPie';

    public function data()
    {
        return [
            'filesystems' => array_keys(Configure::read(CONFIGURE_KEY)),
        ];
    }
}