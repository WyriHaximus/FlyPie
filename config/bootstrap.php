<?php

use Cake\Core\Configure;

// Debug kit panel
/*
if (Configure::read('debug')) {
    Configure::write('DebugKit.panels', array_merge(
        (array)Configure::read('DebugKit.panels'),
        [
            'WyriHaximus/FlyPie.Filesystem',
        ]
    ));

    Router::plugin('WyriHaximus/FlyPie', function ($routes) {
        $routes->extensions('json');
        $routes->connect(
            '/toolbar/clear_cache',
            ['controller' => 'Toolbar', 'action' => 'clearCache']
        );
    });
}
*/

/**
 * DiC module registration
 */
/*
Configure::write('PipingBag.modules', array_merge(
    (array)Configure::read('PipingBag.modules'),
    [
        //
    ]
));
*/
