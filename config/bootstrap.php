<?php

use Cake\Core\Configure;

// Debug kit panel
if (Configure::read('debug')) {
    Configure::write('DebugKit.panels', array_merge(
        (array)Configure::read('DebugKit.panels'),
        [
            'WyriHaximus/FlyPie.Filesystem',
        ]
    ));
}
