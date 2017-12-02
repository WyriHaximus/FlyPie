<?php

use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Adapter\Local;

return [
    'WyriHaximus' => [
        'FlyPie' => [
            'thumbnails' => [
                'adapter' => Local::class,

                /**
                 * Array with data required to build the client, no need for it
                 * when you're providing the client (adapter) or factory yourself.
                 *This array is passed into the constructor using
                 * ReflectionClass::newInstanceArgs So the first item in the array
                 * is the first argument, the second item the second argument etc etc.
                 * See the FTP example for adapters that require an associative array
                 * with configuration as first argument.
                 */
                'vars' => [
                    '/path/to/thumbnails/'
                ],

                // Optional, provide a set up client (adapter).
                'client' => new stdClass(),

                // Optional, provide a factory that builds the client (adapter).
                'factory' => function() { // Build client via callback
                    return new stdClass();
                },

                // Or

                'factory' => [$class, 'method'], // Array call user func.

                // Or

                'factory' => 'Class::method', // String call user func, this is going to be interesting with the client from a event.

                // Or

                'factory' => 'Vendor.Plugin.Event.Name', // Retrieve the client (adapter) from the result property of an event.
            ],

            // FTP example
            'some-ftp-server' => [
                'adapter' => Ftp::class,
                'vars' => [
                    [
                        'host' => 'example.com',
                        'username' => 'username',
                        'password' => 'password',
                    ],
                ],
            ],
        ],
    ],
];
